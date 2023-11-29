<?php

namespace App\Http\Controllers;

use App\Imports\OrdersImport;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

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
}
