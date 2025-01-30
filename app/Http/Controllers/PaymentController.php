<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Order_details;
use App\Models\Payment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\alert;

class PaymentController extends Controller
{

    public function index()
    {

        return response()->json(['message' => 'PayPal API is ready']);
    }

    public function payment(Request $request)
    {


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $amount = $request->input('amount'); // المبلغ المطلوب
        $order_Id = $request->input('order_id'); // معرف الطلب


        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success',['amount'=>$amount,'user_id'=>$request->user_id,'order_id'=>$order_Id,'cartId'=>$request->cartId]),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $amount
                    ]
                ]
            ]
        ]);

        return response()->json($response);


    }


    public function paymentCancel()
    {
        return redirect()
              ->route('paypal')
              ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }



    public function paymentSuccess(Request $request)
    {

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // الحصول على تفاصيل الدفع باستخدام token
        $response = $provider->capturePaymentOrder($request->token);

        // استخراج transaction_id من الاستجابة
        $transactionId = $response['id'] ?? null;

        ////Get cart id
        $cartId = $request->cartId;
        $amount = $request->amount;


        // حساب العمولة والمبلغ المستحق للبائع
        // $commission = $amount * 0.3;
        // $sellerAmount = $amount - $commission;
        $orderId = $request->order_id;

        // جلب تفاصيل المنتجات المرتبطة بالطلب
        $orderDetails = Order_details::where('order_id', $orderId)->get();

        if ($orderDetails->isEmpty()) {
            return response()->json(['message' => 'No products found for this order'], 400);
        }

        // تجميع المبالغ لكل بائع
        // $sellersAmounts = [];

        //// تخزين البائعين
        $seelers =[];
        $count = 0;
        foreach ($orderDetails as $detail) {
            $product = Product::find($detail->product_id);

            if (!$product) {
                continue; // تجاوز المنتجات غير الصالحة
            }

            $sellerId = $product->user_id; // معرف البائع


            // $productAmount = $detail->price; // سعر المنتج
            $productAmount = $product->price; // سعر المنتج

            $commission = $productAmount * 0.3;
            $sellerAmount = $productAmount - $commission;

            // تحقق مما إذا كان البائع موجودًا في المصفوفة
            if (isset($seelers[$sellerId])) {
                // أضف المبلغ المستحق للبائع
                $seelers[$sellerId]['sellerAmount'] += $sellerAmount;
                $seelers[$sellerId]['commission'] += $commission;
            }
            else {
                // إذا لم يكن البائع موجودًا، قم بإضافته
                $seelers[$sellerId] = [
                    'sellerId' => $sellerId,
                    'sellerAmount' => $sellerAmount,
                    'commission'=>$commission
                ];
            }
            $count++;

        }

    // تخزين بيانات الدفع لكل بائع
    foreach ($seelers as $seller) {
        Payment::create([
            'user_id' => $request->user_id, // معرف المشتري
            'seeler_id' => $seller['sellerId'], // معرف البائع
            'order_id' => $orderId, // معرف الطلب
            'transaction_id' => $transactionId, // معرف العملية
            'method' => 'PayPal', // طريقة الدفع
            'status' => 'success',
            'amount' => $amount, // المبلغ الإجمالي
            'commission' => $seller['commission'], // عمولة الموقع
            'sellerAmount' => $seller['sellerAmount'], // المبلغ المستحق للبائع
            'available_at' => Carbon::now()->addDays(3), // موعد الإتاحة للسحب
        ]);
    }
    Order::where('id',$orderId)->where('status','hold')->update(['status'=>'compleated']);
    Cart::where('user_id',$request->user_id)->where('status',0)->update(['status'=>1]);

        ////إرسال استجابة للمستخدم
        // return response()->json([
        //     'message' => 'Payment successful',
        //     'transaction_id' => $transactionId,
        // ]);

        return redirect()->away(url("/#/product-download/{$cartId}"));

}

    public function withdraw(Request $request)
    {
        $userId = Auth::id();

        $payments = Payment::where('user_id', $userId)
            ->where('available_at', '<=', Carbon::now())
            ->where('withdrawn', false)
            ->get();

        $totalAmount = $payments->sum('seller_amount');

        if ($totalAmount > 0) {
            // تحديث حالة السحب
            foreach ($payments as $payment) {
                $payment->update(['withdrawn' => true]);
            }

            return response()->json([
                'message' => 'Withdrawal successful',
                'total_amount' => $totalAmount,
            ]);
        }

        return response()->json(['message' => 'No funds available for withdrawal'], 400);
    }

    public function seelerStock($seeler_id){
        $stocks = Payment::where('seeler_id',$seeler_id)->select('sellerAmount')->get();
        return response()->json($stocks);
    }

}
