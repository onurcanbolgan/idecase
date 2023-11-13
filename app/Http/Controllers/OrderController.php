<?php

namespace App\Http\Controllers;

use App\Jobs\CreateOrder;
use App\Jobs\DeleteOrder;
use App\Jobs\UpdateOrder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('error-handler');
    }

    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $this->validateOrderData($request);
        if ($data instanceof JsonResponse) {
            return $data;
        }
        dispatch(new CreateOrder($data));
        return response()->json(['message' => 'Sipariş oluşturma işlemi sıraya alındı'], 202);
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Sipariş bulunamadı'], 404);
        }

        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateOrderData($request);
        if ($data instanceof JsonResponse) {
            return $data;
        }
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Sipariş bulunamadı'], 404);
        }

        dispatch(new UpdateOrder($data,$order));
        return response()->json(['message' => 'Sipariş güncelleme işlemi sıraya alındı'], 202);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Sipariş bulunamadı'], 404);
        }

        dispatch(new DeleteOrder($order));
        return response()->json(['message' => 'Sipariş silme işlemi sıraya alındı'], 202);
    }

    private function validateOrderData(Request $request)
    {
        $data = $request->validate([
            'customerId' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.productId' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $items = $request->input('items');
        foreach ($items as $item) {
            $product = Product::find($item['productId']);

            if (!$product || $product->stock < $item['quantity']) {
                return response()->json(['error' => 'Ürün stokta yetersiz.'], 422);
            }
        }
        return $data;
    }

}
