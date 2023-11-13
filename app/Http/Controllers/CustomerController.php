<?php

namespace App\Http\Controllers;

use App\Jobs\CreateCustomer;
use App\Jobs\DeleteCustomer;
use App\Jobs\UpdateCustomer;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('error-handler');
    }
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'since' => 'required|date',
            'revenue' => 'required|numeric',
        ]);

        dispatch(new CreateCustomer($validatedData));
        return response()->json(['message' => 'Müşteri oluşturma işlemi sıraya alındı'], 202);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı'], 404);
        }

        return response()->json($customer, 200);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'since' => 'required|date',
            'revenue' => 'required|numeric',
        ]);

        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı'], 404);
        }

        dispatch(new UpdateCustomer($validatedData,$customer));
        return response()->json(['message' => 'Müşteri güncelleme işlemi sıraya alındı'], 202);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Müşteri bulunamadı'], 404);
        }

        dispatch(new DeleteCustomer($customer));
        return response()->json(['message' => 'Müşteri silme işlemi sıraya alındı'], 202);
    }

}
