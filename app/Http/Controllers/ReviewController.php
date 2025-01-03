<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class ReviewController extends Controller
{
   public function index(){

        return Review::all();
   }

   public function show($id){
    $review  = Review::where('id',$id)->select('*')->first();
    return response()->json($review);
   }

   public function productComments($product_id){
        $comments = Review::where('product_id',$product_id)->select('comment')->orderByDesc('created_at')->get();
        return response()->json($comments);
   }

   public function store(Request $request){
        $validated = $request->validate([
            'product_id'=>'required',
            'user_id'=>'required|string',
            'comment'=>'string'
        ]);

        $dataToInsert['user_id'] = $request->user_id;
        $dataToInsert['product_id'] = $request->product_id;
        $dataToInsert['comment'] = $request->comment;
        $dataToInsert['rating'] = $request->rating;
        $review = Review::create($dataToInsert);

        return response()->json($dataToInsert);
   }

   public function destroy($id){
        $review = Review::find($id);
        $review->delete();
        return response()->json(['message'=>'Review was deleted']);
   }
}
