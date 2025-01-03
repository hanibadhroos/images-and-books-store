<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_details;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $userId = $request->user_id;
        $cartItems = $request->cartItems; // قائمة المنتجات في السلة

        // التحقق من وجود طلب بنفس العناصر
        $existingOrder = Order::where('user_id', $userId)
            ->where('status', 'hold')
            ->whereHas('orderDetails', function ($query) use ($cartItems) {
                $query->whereIn('product_id', array_column($cartItems, 'product_id'));
            })
            ->first();


            if ($existingOrder) {
                // الطلب موجود بالفعل -> الانتقال إلى الدفع
                return response()->json([
                    'status' => 'exists',
                    'order_id' => $existingOrder->id
                ]);
            }


            // إنشاء طلب جديد إذا لم يكن موجودًا
            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $request->input('total_price'),
                'status' => 'hold',
            ]);


                    // إضافة العناصر إلى جدول order_items
        foreach ($cartItems as $item) {
            Order_details::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['product']['price'],
            ]);
        }

        return response()->json([
            'status' => 'created',
            'order_id' => $order->id
        ]);


        // $dataToInsert['user_id'] = $request->user_id;
        // $dataToInsert['total_price'] = $request->total_price;
        // $dataToInsert['status'] = $request->status;

        // $order= Order::create($dataToInsert);
        // return response()->json($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }

    public function holdOrders(){
        $orders = Order::where('status','hold')->select('*')->get();
        return response()->json($orders);
    }
}
