<?php

namespace App\Http\Controllers;

use App\Models\Cart_items;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemsController extends Controller
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
        $dataToInsert['cart_id']= $request->cart_id;
        $dataToInsert['product_id']= $request->product_id;
        $dataToInsert['status']= $request->status;
        $dataToInsert['quantity']= $request->quantity;
        $item = Cart_items::create($dataToInsert);
        return response()->json($item,201);
    }

    /**
     * Display the specified resource.
     */
    public function show($Item_id)
    {
        return response()->json(Cart_items::where('id',$Item_id)->select('*')->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $Item_id)
    {
        $dataToUpdate['quantity'] = $request->quantity;
        $item = Cart_items::where('id',$Item_id)->update($dataToUpdate);
        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $Item_id)
    {
        Cart_items::where('id',$Item_id)->delete();
        return response()->json(['Item deleted successfully']);
    }

    public function userItems($cart_id){
        // $items = Cart_items::where('cart_id',$cart_id)->select('*')->get();
        $items = Cart_items::with('product')
        ->where('cart_id', $cart_id)
        ->get();
        return response()->json($items);
    }
}
