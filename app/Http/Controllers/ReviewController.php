<?php

namespace App\Http\Controllers;

use App\Jobs\SendReportNotification;
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
        $comments = Review::with('user')->where('product_id',$product_id)->where('comment','!=','null')->select('*')->orderByDesc('created_at')->get();
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

        return response()->json($review);
   }

   public function destroy($id){
        $review = Review::find($id);
        $review->delete();
        return response()->json(['message'=>'Review was deleted']);
   }

   //// For inform about the product ---> البلاغ عن المنتج
   public function inform(Request $request,$produc_id){
        $dataToInsert['product_id']= $produc_id;
        $dataToInsert['user_id']= $request->user_id;
        $dataToInsert['inform']= $request->inform;
        $inform = Review::create($dataToInsert);

        SendReportNotification::dispatch($request->inform);

        return response()->json($inform);

   }
   public function informedProducts(){
    $products = Review::with('product.user')->whereNotNull('inform')->get();
    return redirect()->away(url('/#/informed-product'));
    // return response()->json($products);
   }
   public function allLikes(){
    $likes = Review::where('rating ',1)->get();
    return response()->json($likes);
   }
}
