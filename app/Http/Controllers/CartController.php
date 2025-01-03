<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Exception;
use Illuminate\Http\Request;

class CartController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Cart::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // تأكد من استخدام $request->all() أو استخراج البيانات بشكل صحيح
        $validatedData = $request->validate([
            'user_id' => 'required|string',
            'status' => 'required|boolean',
        ]);

        $cart = Cart::create($validatedData);
        return response()->json($cart,201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cart = Cart::where('id',$id)->select('*')->first();
        return response()->json($cart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cart)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cart_id)
    {
        Cart::where('id',$cart_id)->delete();
        return response()->json(['Cart deleted successfully']);
    }

    ////// Check if the user has a Cart with active status.
    public function isCreated($user_id){
        $isCreated = Cart::where('user_id',$user_id)->where('status',0)->first();
        if($isCreated){
            return response()->json(true);
        }
        else{
            return response()->json(false);
        }
    }

    public function user_cart($user_id){
        try{
            $cart  = Cart::where('user_id',$user_id)->where('status',0)->select('*')->first();
            return response()->json($cart);
        }
        catch(Exception $e){
            return response()->json(['message'=>'Error while fetching the items',$e]);
        }

    }
}
