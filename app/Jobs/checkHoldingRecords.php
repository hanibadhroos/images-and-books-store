<?php

namespace App\Jobs;

use App\Models\Cart;
use App\Models\Cart_items;
use App\Models\Order;
use App\Models\Order_details;
use App\Notifications\IncompleteRecordNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class checkHoldingRecords implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $models = [
            'orders' => Order::class,
            // 'order_details' => Order_details::class,
            'carts' => Cart::class,
            // 'cart_items' => Cart_items::class,
        ];

        foreach ($models as $type => $model) {
            // $records = $model::where('created_at', '<', now()->subDay())->get();
            $records = $model::where('status','hold')->get();

            foreach ($records as $record) {
                $user = $record->user; // افترض أن السجل مرتبط بـ user
                if ($user) {
                    $user->notify(new IncompleteRecordNotification($type));
                }
            }
        }

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
