<?php

namespace App\Http\Controllers;

use App\Jobs\OrderBulkImportJob;
use App\Jobs\OrderCreateJob;
use App\Models\OrdersSummary;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EyezApi extends Controller
{
    public function createOrUpdateOrderSummary(Request $request)
    {
        $validator = $this->validateOrderSummary($request);

        if ($validator->fails()) {
            return response()->json([
                'errors' => true,
                'message' => $validator->errors()
            ]);
        }

        if ($store = Store::where('dep_id', $request->input('store_id'))->first()) {
            $summaryDate = Carbon::parse($request->input('summary_date'));

            if ($orderSummary = $store->ordersSummary()->whereDate('summary_date', $summaryDate)->first()) {
                $orderSummary->orders_count = $request->input('orders_count');
                $orderSummary->orders_total = $request->input('orders_total');
            } else {
                $orderSummary = new OrdersSummary();
                $orderSummary->store_id = $store->id;
                $orderSummary->orders_count = $request->input('orders_count');
                $orderSummary->orders_total = $request->input('orders_total');
                $orderSummary->summary_date = $summaryDate->format('Y-m-d H:i:s');
            }

            $orderSummary->save();
            return [
                'errors' => false,
                'message' => 'Successfully created.'
            ];
        } else {
            return response()->json([
                'errors' => true,
                'message' => 'Need to complete eyez settings in profile settings first.'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function orderCreate(Request $request)
    {
        $validator = $this->validateOrder($request);

        if ($validator->fails()) {
            return response()->json([
                'errors' => true,
                'message' => $validator->errors()
            ]);
        }

        if ($store = Store::where('dep_id', $request->input('store_id'))->first()) {
            OrderCreateJob::dispatch($store, (object)$request->all());

            return [
                'errors' => false,
                'message' => 'Order successfully created.'
            ];
        } else {
            return response()->json([
                'errors' => true,
                'message' => 'Need to complete eyez settings in profile settings first.'
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateApi(Request $request)
    {
        $user = $request->user();

        if ($user->stores) {
            return response()->json([
                'errors' => false,
                'message' => 'API token is valid',
                'data' => $user->stores()->select('id', 'dep_id', 'name')->get()
            ]);
        } else {
            return response()->json([
                'errors' => true,
                'message' => 'API token is not valid',
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function orderBulkImport(Request $request)
    {
        $orders = $request->json()->all();
        $validator = $this->validateOrder($orders[0]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => true,
                'message' => $validator->errors()
            ]);
        }

        \Log::info('Order bulk import',['o' => $orders]);

        $store = Store::where('dep_id', $orders[0]['store_id'])->first();
        $store = $store ?? Store::find($orders[0]['store_id']);

        if ($store) {
            OrderBulkImportJob::dispatch($store, $orders);

            return [
                'errors' => false,
                'message' => 'Order successfully created.'
            ];
        } else {
            return response()->json([
                'errors' => true,
                'message' => 'Need to complete eyez settings in profile settings first.'
            ]);
        }
    }

    /**
     * @param Request|array $request
     * @return \Illuminate\Validation\Validator
     */
    private function validateOrder(Request|array $request)
    {
        $rules = [
            'store_id' => 'required|integer',
            'order_id' => 'required|string',
            'order_date' => 'required|date',
            'items_count' => 'required|integer',
            'order_total' => 'required|numeric'
        ];

        $toValidate = is_array($request) ? $request : $request->all();
        // Validate the request data
        return Validator::make($toValidate, $rules);
    }

    private function validateOrderSummary(Request|array $request)
    {
        // Define validation rules
        $rules = [
            'store_id' => 'required|integer',
            'summary_date' => 'required|date_format:Y-m-d',
            'orders_count' => 'required|integer',
            'orders_total' => 'required|numeric'
        ];

        $toValidate = is_array($request) ? $request : $request->all();
        // Validate the request data
        return Validator::make($toValidate, $rules);
    }
}
