<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCategory;
use App\Jobs\DeleteCategory;
use App\Jobs\UpdateCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use mysql_xdevapi\Exception;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json($category);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required']);

        dispatch(new CreateCategory($data));
        return response()->json(['message' => 'Kategori oluşturma işlemi sıraya alındı'], 202);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori bulunamadı'], 404);
        }

        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate(['name' => 'required']);

        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori bulunamadı'], 404);
        }
        dispatch(new UpdateCategory($data,$category));
        return response()->json(['message' => 'Kategori güncelleme işlemi sıraya alındı'], 202);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Kategori bulunamadı'], 404);
        }

        dispatch(new DeleteCategory($category));
        return response()->json(['message' => 'Kategori silme işlemi sıraya alındı'], 202);
    }
}
