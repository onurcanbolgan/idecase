<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiscountController extends Controller
{
    public function calculateDiscount($orderId): JsonResponse
    {
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['error' => 'Sipariş bulunamadı'], 404);
        }

        $discounts = [];
        $totalDiscount = 0;
        $appliedDiscounts = [];

        foreach ($order->items as $item) {
            $product = Product::find($item['productId']);
            $itemTotal = $item['quantity'] * $item['unitPrice'];
            $subtotal = $itemTotal;

            // Kural 2: 2 ID'li kategoriye ait bir üründen 6 adet satın alındığında, bir tanesi ücretsiz olarak verilir.
            if ($product->category == 2 && $item['quantity'] >= 6) {
                $freeItemCount = (int)($item['quantity'] / 6);
                $discountAmount = $freeItemCount * $item['unitPrice'];
                $totalDiscount += $discountAmount;

                $discounts[] = [
                    'discountReason' => 'BUY_5_GET_1',
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($subtotal, 2),
                ];
            }

            // Kural 3: 1 ID'li kategoriden iki veya daha fazla ürün satın alındığında, en ucuz ürüne %20 indirim yapılır.
            if ($product->category == 1 && $item['quantity'] >= 2) {
                $discountKey = '20_PERCENT_OFF_CHEAPEST';

                if (!isset($appliedDiscounts[$discountKey])) {
                    $discountedPrice = $this->getDiscountedProductPrice($product);
                    $discountAmount = ($item['unitPrice'] - $discountedPrice) * $item['quantity'];
                    $totalDiscount += $discountAmount;

                    $discounts[] = [
                        'discountReason' => $discountKey,
                        'discountAmount' => number_format($discountAmount, 2),
                        'subtotal' => number_format($subtotal, 2),
                    ];

                    $appliedDiscounts[$discountKey] = true;
                }
            }

            // Diğer indirim kuralları buraya eklenir...

            // İndirim uygulandıktan sonra subtotal güncellenir.
            $subtotal -= $totalDiscount;
        }

        // Kural 1: Toplam 1000TL ve üzerinde alışveriş yapan bir müşteri, siparişin tamamından %10 indirim kazanır.
        if ($order->total >= 1000) {
            $discountKey = '10_PERCENT_OVER_1000';

            if (!isset($appliedDiscounts[$discountKey])) {
                $discountAmount = $order->total * 0.10;
                $totalDiscount += $discountAmount;

                $discounts[] = [
                    'discountReason' => $discountKey,
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($subtotal, 2),
                ];

                $appliedDiscounts[$discountKey] = true;
            }
        }

        $discountedTotal = $order->total - $totalDiscount;

        return response()->json([
            'orderId' => $orderId,
            'discounts' => $discounts,
            'totalDiscount' => number_format($totalDiscount, 2),
            'discountedTotal' => number_format($discountedTotal, 2),
        ]);
    }

    private function getDiscountedProductPrice(Product $product): float
    {
        return $product->price * 0.80;
    }

    private function getCategoryItemCount(Product $product): int
    {
        return Product::where('category', $product->category)->count();
    }
}
