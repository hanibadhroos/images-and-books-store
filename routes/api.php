<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function(){

});

//Product API
Route::apiResource('product',ProductController::class);
Route::get('/images',[ProductController::class,'images']);
Route::get('/books',[ProductController::class,'books']);
Route::get('/myProducts/{user_id}',[ProductController::class,'myProducts']);
Route::get('/product/search',[ProductController::class,'search']);

//Category API
Route::apiResource('category',CategoryController::class);

// Users API
Route::apiResource('users',UserController::class);

//// Followers
// Route::apiResource('followers', FollowerController::class);


///// Cart API
Route::apiResource('/cart',CartController::class);
Route::get('/cart/isCreated/{user_id}',[CartController::class,'isCreated']);
Route::get('/userCart/{user_id}',[CartController::class,'user_cart']);

///// cart_items API
Route::apiResource('/cartItems',CartItemsController::class);
Route::get('/userItems/{cart_id}',[CartItemsController::class,'userItems']);

///// Orders API
Route::apiResource('orders',OrderController::class);
Route::get('/holdOrders',[OrderController::class,'holdOrders']);

////// Order Items API
Route::apiResource('orderDetails',OrderDetailsController::class);


///// Payment API
Route::get('paypal', [PaymentController::class, 'index'])->name('paypal');
Route::get('paypal/payment', [PaymentController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PaymentController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('paypal.payment/cancel');


///// Review API
Route::apiResource('review',ReviewController::class);
Route::get('reviews/productComments/{product_id}',[ReviewController::class,'productComments']);

//// For Authentication
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email'=>'required|email',
        'password'=>'required'
    ]);

    $user = User::where('email',$request->email)->first();

    // if user is empty or password is no equal
    if(!$user | !Hash::check($request->password, $user->password)){
        throw new ValidationException('email are incorrect.');
    }

    return $user->createToken($request->device_name)->plainTextToken;

});



Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class, 'login']);

Route::get('/getUser',[AuthController::class, 'getUser'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);
