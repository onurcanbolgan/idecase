<?php

namespace App\Http\Controllers;

use App\Jobs\CreateProduct;
use App\Jobs\DeleteProduct;
use App\Jobs\UpdateProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('error-handler');
    }

    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $this->validateProductData($request);

        dispatch(new CreateProduct($data));
        return response()->json(['message' => 'Ürün oluşturma işlemi sıraya alındı'], 202);
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı'], 404);
        }
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $data = $this->validateProductData($request);

        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı'], 404);
        }

        dispatch(new UpdateProduct($data,$product));
        return response()->json(['message' => 'Ürün güncelleme işlemi sıraya alındı'], 202);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Ürün bulunamadı'], 404);
        }
        dispatch(new DeleteProduct($product));
        return response()->json(['message' => 'Ürün silme işlemi sıraya alındı'], 202);
    }

    private function validateProductData(Request $request){
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        return $data;
    }
}
