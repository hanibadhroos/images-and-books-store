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
use App\Jobs\SendEmailVerificationJob;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/authUser',[ProductController::class,'authUser']);

//Product API
Route::apiResource('product',ProductController::class);
Route::get('/images',[ProductController::class,'images']);
Route::get('/books',[ProductController::class,'books']);
Route::get('/myProducts/{user_id}',[ProductController::class,'myProducts'])->middleware(['auth:sanctum']);
Route::get('/product/search',[ProductController::class,'search']);
Route::get('/download/{path}',[ProductController::class,'download'])->where('path', '.*');

//Category API
Route::apiResource('category',CategoryController::class);

// Users API
Route::apiResource('users',UserController::class);


///// Cart API
Route::apiResource('/cart',CartController::class);
Route::get('/cart/isCreated/{user_id}',[CartController::class,'isCreated']);
Route::get('/userCart/{user_id}',[CartController::class,'user_cart']);

///// cart_items API
Route::apiResource('/cartItems',CartItemsController::class);
Route::get('/userItems/{cart_id}',[CartItemsController::class,'userItems']);
Route::get('/product-download/{cart_id}',[CartItemsController::class,'paidItems']);

///// Orders API
Route::apiResource('orders',OrderController::class);
Route::get('/holdOrders',[OrderController::class,'holdOrders']);

////// Order Items API
Route::apiResource('orderDetails',OrderDetailsController::class);


///// Payment API
Route::get('paypal', [PaymentController::class, 'index'])->name('paypal');
Route::post('paypal/payment', [PaymentController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PaymentController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('paypal.payment/cancel');
/////Wthdrow
Route::post('/withdraw', [PaymentController::class, 'withdraw'])->middleware('auth:sanctum');
///// Seeler Stock
Route::get('/userStock/{seeler_id}',[PaymentController::class,'seelerStock']);
////Available stock
Route::get('/available-stock/{seeler_id}',[PaymentController::class,'availableStock']);
//////Most selling
Route::get('/most-selling',[PaymentController::class, 'mostSelling']);

///// Review API
Route::apiResource('review',ReviewController::class);
Route::get('reviews/productComments/{product_id}',[ReviewController::class,'productComments']);
Route::post('/inform/{product_id}',[ReviewController::class,'inform']);
Route::get('/get-all-likes',[ReviewController::class,'allLikes']);
Route::get('/informedProducts',[ReviewController::class,'informedProducts'])->name('informs');
Route::get('/isLiked/{product_id}/{user_id}',[ReviewController::class,'Liked']);



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

///// Routes For Email verification
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    //  $request->fulfill();
    //  return response()->json(['message' => 'Email verified successfully']);

    $user = User::find($id);

    // تحقق من وجود المستخدم
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // التحقق من صحة الرابط
    if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Invalid verification link'], 403);
    }

    // التحقق إذا تم التفعيل مسبقاً
    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email already verified, back to website']);
    }

    // تحديث حقل email_verified_at
    $user->markEmailAsVerified();
    return response()->json(['message'=>"Your email verified successfully"]);

})->middleware([ 'signed'])->name('verification.verify');

/////إرسال رابط التحقق
Route::post('/email/verification-notification', function (Request $request) {
    $user = $request->user();
    SendEmailVerificationJob::dispatch($user);
    return response()->json(['message' => 'Verification email sent']);
})->middleware('auth:sanctum')->name('verification.send');


Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class, 'login']);

Route::get('/getUser',[AuthController::class, 'getUser'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);
