<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    private $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, Order $order)
    {
        $this->data = $data;
        $this->order = $order;
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
        $existingItemKey = null;

        foreach ($data['items'] as $item) {
            $product = Product::find($item['productId']);
            $unitPrice = $product->price;

            // Siparişte var olan ürünü kontrol et
            $existingItem = collect($this->order->items)->first(function ($value) use ($item) {
                return $value['productId'] == $item['productId'];
            });

            if ($existingItem) {
                $oldQuantity = $existingItem['quantity'];

                // Stok azaltma veya artırma işlemi
                $stockDifference = $item['quantity'] - $oldQuantity;
                if ($stockDifference > 0) {
                    $product->decreaseStock($stockDifference);
                } elseif ($stockDifference < 0) {
                    $product->increaseStock(abs($stockDifference));
                }

            } else {
                // Stok azaltma işlemi
                $product->decreaseStock($item['quantity']);
            }
            $orderItems[] = [
                'productId' => $item['productId'],
                'quantity' => $item['quantity'],
                'unitPrice' => $unitPrice,
                'total' => $item['quantity'] * $unitPrice,
            ];
            $totalOrderAmount += $item['quantity'] * $unitPrice;
        }

        // Siparişi güncelle veya tamamla
        $orderData = [
            'customerId' => $data['customerId'],
            'items' => $orderItems,
            'total' => $totalOrderAmount,
        ];

        $this->order->update($orderData);
    }
    /**
     * Verilen bir ürün ID'sine göre $orderItems dizisindeki anahtarı bulma
     */
    private function findItemKey($orderItems, $productId)
    {
        foreach ($orderItems as $key => $item) {
            if ($item['productId'] == $productId) {
                return $key;
            }
        }

        return null;
    }
}
