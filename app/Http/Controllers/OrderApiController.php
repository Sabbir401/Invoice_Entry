<?php
namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
{
    public function showOrder($id = null)
    {
        if ($id == '') {
            $orders = order::get();
            foreach ($orders as $order) {
                $order['items'] = json_decode($order['items']);
                $order['other'] = json_decode($order['other']);
            }
            return response()->json(['orders' => $orders], 200);
        } else {
            $order = order::find($id);
            $order['items'] = json_decode($order['items']);
            $order['other'] = json_decode($order['other']);

            return response()->json(['order' => $order], 200);
        }
    }

    public function addOrder(Request $request)
    {
        if ($request->ismethod('post')) {
            $data = $request->all();

            $rules = [
                'order_date' => 'required|date',
                'delivery_date' => 'required|date',
                'day' => 'required',
                'delivery_number' => 'required',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'telephone_number' => 'required|string',
                'address' => 'required|string',
                'zip_code' => 'required|string',
                'would' => 'required',
                'items' => 'required',
                'other' => 'required',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $store = new order();
            $store->order_date = $data['order_date'];
            $store->delivery_date = $data['delivery_date'];
            $store->day = $data['day'];
            $store->delivery_number = $data['delivery_number'];
            $store->first_name = $data['first_name'];
            $store->last_name = $data['last_name'];
            $store->telephone_number = $data['telephone_number'];
            $store->address = $data['address'];
            $store->zip_code = $data['zip_code'];
            $store->would = $data['would'];
            $store->floor_number = $data['floor_number'];
            $store->items = json_encode($data['items']);
            $store->other = json_encode($data['other']);
            $store->save();

            $message = 'Entry Added Succesfully';
            return response()->json(['message' => $message], 201);
        }
    }


    public function addMultipleOrder(Request $request)
    {
        if ($request->ismethod('post')) {
            $order_data = $request->all();

            $rules = [
                'orders.*.order_date' => 'required|date',
                'orders.*.delivery_date' => 'required|date',
                'orders.*.day' => 'required',
                'orders.*.delivery_number' => 'required',
                'orders.*.first_name' => 'required|string',
                'orders.*.last_name' => 'required|string',
                'orders.*.telephone_number' => 'required|string',
                'orders.*.address' => 'required|string',
                'orders.*.zip_code' => 'required|string',
                'orders.*.would' => 'required',
            ];
            $validator = Validator::make($order_data, $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            foreach ($order_data['orders'] as $data) {
                $store = new order();
                $store->order_date = $data['order_date'];
                $store->delivery_date = $data['delivery_date'];
                $store->day = $data['day'];
                $store->delivery_number = $data['delivery_number'];
                $store->first_name = $data['first_name'];
                $store->last_name = $data['last_name'];
                $store->telephone_number = $data['telephone_number'];
                $store->address = $data['address'];
                $store->zip_code = $data['zip_code'];
                $store->would = $data['would'];
                $store->floor_number = $data['floor_number'];
                $store->items = json_encode($data['items']);
                $store->other = json_encode($data['other']);
                $store->save();

                $message = 'Entry Added Succesfully';
            }
            return response()->json(['message' => $message], 201);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        if ($request->ismethod('put')) {
            $data = $request->all();

            $rules = [
                'order_date' => 'required|date',
                'delivery_date' => 'required|date',
                'day' => 'required',
                'delivery_number' => 'required',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'telephone_number' => 'required|string',
                'address' => 'required|string',
                'zip_code' => 'required|string',
                'would' => 'required',
                'items' => 'required',
                'other' => 'required',
            ];
            $validator = Validator::make($data, $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }


            $store = order::findOrFail($id);
            $store->order_date = $data['order_date'];
            $store->delivery_date = $data['delivery_date'];
            $store->day = $data['day'];
            $store->delivery_number = $data['delivery_number'];
            $store->first_name = $data['first_name'];
            $store->last_name = $data['last_name'];
            $store->telephone_number = $data['telephone_number'];
            $store->address = $data['address'];
            $store->zip_code = $data['zip_code'];
            $store->would = $data['would'];
            $store->floor_number = $data['floor_number'];
            $store->items = json_encode($data['items']);
            $store->other = json_encode($data['other']);
            $store->save();

            $message = 'Entry Updated Succesfully';
            return response()->json(['message' => $message], 202);
        }
    }

    public function deleteOrder($id = null)
    {
        order::findOrFail($id)->delete();
        $message = 'Entry Deleted Succesfully';
        return response()->json(['message' => $message], 200);
    }

    public function deleteOrderJson(Request $request)
    {
        if ($request->isMethod('delete')) {
            $data = $request->all();
            order::where('id', $data['id'])->delete();
            $message = 'Entry Deleted Succesfully';
            return response()->json(['message' => $message], 200);
        }
    }
}
