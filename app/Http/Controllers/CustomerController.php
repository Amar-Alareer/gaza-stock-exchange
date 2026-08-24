<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = Customer::all();

        return response()->json($customers);
    }



    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

// إضافة عميل جديد
public function store(Request $request)
{
    $validated = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'phone' => 'nullable|string|max:20',
    ]);

    $customer = Customer::create($validated);

    return response()->json([
        'message'  => 'تم إضافة العميل بنجاح',
        'customer' => $customer,
    ], 201);
}



    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return response()->json([
            'message'  => 'تم تحديث بيانات العميل بنجاح',
            'customer' => $customer,
        ]);

}
}
