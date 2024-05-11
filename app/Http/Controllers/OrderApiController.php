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
            return response()->json(['orders' => $orders], 200);
        } else {
            $orders = order::find($id);
            return response()->json(['orders' => $orders], 200);
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
            ];
            $validator = Validator::make($data, $rules);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
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

            $message = 'Oder Added Succesfully';
            return response()->json(['message' => $message], 201);
        }
    }


    public function addMultipleOrder(Request $request)
    {
        if ($request->ismethod('post')) {
            $order_data = $request->all();

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
                // $store->items = $data['email'];
                // $store->other = $data['email'];
                $store->save();

                $message = 'Order Added Succesfully';
            }
            return response()->json(['message' => $message], 201);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        if ($request->ismethod('put')) {
            $data = $request->all();


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
            // $store->items = $data['email'];
            // $store->other = $data['email'];
            $store->save();

            $message = 'Order Updated Succesfully';
            return response()->json(['message' => $message], 202);
        }
    }

    public function deleteOrder($id = null)
    {
        order::findOrFail($id)->delete();
        $message = 'Order Deleted Succesfully';
        return response()->json(['message' => $message], 200);
    }

    public function deleteOrderJson(Request $request)
    {
        if ($request->isMethod('delete')) {
            $data = $request->all();
            order::where('id', $data['id'])->delete();
            $message = 'Order Deleted Succesfully';
            return response()->json(['message' => $message], 200);
        }
    }
}
