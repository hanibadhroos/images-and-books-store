<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Laravel\Sanctum\PersonalAccessToken;

class ProductController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category','user'])->select('*')->orderByDesc('created_at')->get();
        $likes = Review::where('rating',1)->get();
        return response()->json(['products'=>$products,'likes'=>$likes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title'=>'required|string',
            'description'=>'required|string',
            'price'=>'numeric|required|',
            'type'=>'required',
            'category_id'=>'required',
            'file_path'=>'required',
            'user_id'=>'required|string',

        ]);
        if($validated){
            $dataToInsert['title']=$request->title;
            $dataToInsert['description']=$request->description;
            $dataToInsert['price']=$request->price;
            $dataToInsert['type']=$request->type;
            $dataToInsert['category_id']=$request->category_id;
            $dataToInsert['user_id'] = $request->user_id;
        }

        ///// For store image
        if($request->hasFile('file_path')){
            if($request->type === 'image'){

                $imageName = time().'.'.$request->file_path->extension();
                $img = Image::read($request->file_path->path());

                $logo = public_path('storage/images/picBook.png');
                $img->place($logo, 'center-center', 10, 40);

                ////// Store in watermarked folder
                $img->save(public_path('storage/uploads/watermarked/'). $imageName);

                ////// Store in original folder
                $file_path = $request->file('file_path')->store('uploads/original','public');

                $dataToInsert['watermark_path']= 'storage/uploads/watermarked/' . $imageName;

                $dataToInsert['file_path']= $file_path;

            }
            else{
                $cover = time().'.'.$request->cover->extension();
                $img = Image::read($request->cover->path());

                $logo = public_path('storage/images/picBook.png');
                $img->place($logo, 'center-center', 10, 40);
                ///// Store images in watermark folder
                $img->save(public_path('storage/uploads/watermarked/'). $cover);
                ////Store orginal cover in Database
                $dataToInsert['cover']= 'storage/uploads/original/' . $cover;

                ///// Store images in watermark database
                $dataToInsert['watermark_path']= 'storage/uploads/watermarked/' . $cover;
                ///// Store orginal file in books folder
                $file_path = $request->file('file_path')->store('uploads/books','public');
                //////store orginal book in db
                $dataToInsert['file_path']= $file_path;

            }
            // $dataToInsert['watermark_path']= 'storage/uploads/watermarked/' . $imageName;
        }
        $product = Product::create($dataToInsert);
        return response()->json($product,201);
    }

    public function download($path){

        $filePath = storage_path('app/public/' . $path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return response()->download($filePath);


    }

    public function show($product_id)
    {
        $product =Product::find($product_id);
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $dataToUpdate['title']= $request->title;
        $dataToUpdate['description']= $request->description;
        $dataToUpdate['price']= $request->price;
        $dataToUpdate['type']= $request->type;
        $dataToUpdate['file_path']= $request->file_path;
        $dataToUpdate['category_id']= $request->category_id;
        $dataToUpdate['user_id']= $request->user_id;

        $product = Product::where('id',$product)->update($dataToUpdate );
        return response($product,200);
        // return response($request,200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json(['message' => 'Product deleted successfully'], 200);
        }
        return response()->json(['message' => 'Product not found'], 404);
    }

    public function myProducts($user_id){
        $products = Product::where('user_id',$user_id)->select('*')->get();
        return response()->json($products);
    }

    public function images(){
        $images = Product::where('type','image')->select('*')->orderByDesc('created_at')->get();
        return response()->json($images);
    }

    public function books(){
        $books = Product::where('type','book')->select('*')->orderByDesc('created_at')->get();
        return response()->json($books);
    }
    public function search(Request $request){
        $query = Product::query();

        //// For Search
        if ( $request->has('search') && $request->search !== '') {
            $query->where('title','like', '%' . $request->search . '%')->where('descriptin','like','%' . $request->search . '%');

            return response()->json($query->get());
        }
    }

    public function authUser(Request $request)
    {
        return response()->json(['user' => Auth::guard('sanctum')->user()]);
    }
}
