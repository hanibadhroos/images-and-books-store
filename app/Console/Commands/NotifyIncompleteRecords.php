<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\Order;
use App\Notifications\IncompleteRecordNotification;
use Illuminate\Console\Command;

class NotifyIncompleteRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:incomplete-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إشعار المستخدمين بالسجلات غير المكتملة';

    /**
     * Execute the console command.
     */
    public function handle()
    {

             // البحث عن السجلات غير المكتملة
             $orders = Order::where('status', 'hold')->get();
             $carts = Cart::where('status', 0)->get();



             // إرسال الإشعارات لكل مستخدم
             foreach ($orders as $order) {
                 $user = $order->user; // Assuming there's a relation 'user' in the Order model
                 $user->notify(new IncompleteRecordNotification("طلب غير مكتمل: #{$order->id}"));
             }



             foreach ($carts as $cart) {
                 $user = $cart->user; // Assuming there's a relation 'user' in the Cart model
                 $user->notify(new IncompleteRecordNotification("سلة تسوق غير مكتملة: #{$cart->id}"));
             }

             $this->info('تم إرسال الإشعارات للسجلات غير المكتملة.');
    }
}
