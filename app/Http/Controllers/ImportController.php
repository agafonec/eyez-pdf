<?php

namespace App\Http\Controllers;

use App\Imports\OrdersImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ImportController extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function ordersView()
    {
        return Inertia::render('ImportOrders', [
            'stores' => $this->user()->stores,
        ]);
    }

    public function orders(Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $this->user()->cache('last_import', 'started', 360);
            $storeId = (int)$request->input('storeId');

            Excel::import(new OrdersImport($storeId), $file);

            return ['errors' => false, 'message' => 'בקרוב כל ההזמנות יהיו מעודכנות'];
        } else {
            \Log::info('No file selected');
            return ['errors' => true, 'message' => 'There was no files selected.'];
        }
    }

    /**
     * @param Request $request
     * @param $file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request, $file)
    {
        $path = storage_path("app/public/samples/{$file}");

        return response()->download($path, $file);
    }

    public function getStatus(Request $request)
    {
        $last_import = $this->user()->cached('last_import');
        \Log::info('get status', ['last_import' => $last_import]);

        return ['lastImport' => $last_import];
    }

    public function cleanStatus(Request $request)
    {
        $this->user()->forgetCached('last_import');
        \Log::info('CLEANED STATUS');
        return true;
    }
}
