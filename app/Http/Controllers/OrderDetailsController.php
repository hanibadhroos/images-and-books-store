<?php

namespace App\Http\Controllers;

use App\Models\Order_details;
use Exception;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'order_id' => 'required|integer|exists:orders,id', // يجب أن يكون موجودًا في جدول الطلبات
                'product_id' => 'required|integer|exists:products,id', // يجب أن يكون موجودًا في جدول المنتجات
                'quantity' => 'required|integer|min:1', // الكمية يجب أن تكون عددًا صحيحًا لا يقل عن 1
                'price' => 'required|numeric|min:0', // السعر يجب أن يكون رقمًا أكبر أو يساوي 0
            ]);
            if ($validated) {
                $dataToInsert['order_id'] =$request->order_id;
                $dataToInsert['product_id'] =$request->product_id;
                $dataToInsert['quantity'] =$request->quantity;
                $dataToInsert['price'] =$request->price;


                $orderDetails = Order_details::create($dataToInsert);
                return response()->json($orderDetails,201);
            }
            else{
                return response()->json(['message'=>'NO data was submitted to store function'],404);
            }

        }
        catch (Exception $e){
            return response()->json("Error while store new oderDetails, into Store Function ===>" . $e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($order_id)
    {
        $orders = Order_details::where('order_id',$order_id)->select('*')->get();
        return response()->json($orders);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order_details $orderDetails)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order_details $orderDetails)
    {
        //
    }
}
