<?php

namespace App\Http\Controllers;

use App\Models\Cart_items;
use App\Models\Product;
use Illuminate\Http\Request;

class CartItemsController extends Controller
{

    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $dataToInsert['cart_id']= $request->cart_id;
        $dataToInsert['product_id']= $request->product_id;
        $dataToInsert['status']= $request->status;
        $dataToInsert['quantity']= $request->quantity;
        $item = Cart_items::create($dataToInsert);
        return response()->json($item,201);
    }

    public function show($Item_id)
    {
        return response()->json(Cart_items::where('id',$Item_id)->select('*')->first());
    }

    public function update(Request $request,  $Item_id)
    {
        $dataToUpdate['quantity'] = $request->quantity;
        $item = Cart_items::where('id',$Item_id)->update($dataToUpdate);
        return response()->json($item);
    }

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
    public function paidItems($cartId){
        $items = Cart_items::where('cart_id',$cartId)->get();
        return redirect("/product-download/{$cartId}");

    }
}
