<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $data = $this->data;

        $totalOrderAmount = 0;
        $orderItems = [];

        foreach ($data['items'] as $item) {
            $product = Product::find($item['productId']);
            $unitPrice = $product->price;

            $orderItems[] = [
                'productId' => $item['productId'],
                'quantity' => $item['quantity'],
                'unitPrice' => $unitPrice,
                'total' => $item['quantity'] * $unitPrice,
            ];

            $totalOrderAmount += $item['quantity'] * $unitPrice;
            $product->decreaseStock($item['quantity']);
        }

        $orderData = [
            'customerId' => $data['customerId'],
            'items' => $orderItems,
            'total' => $totalOrderAmount,
        ];

        Order::create($orderData);
    }
}
