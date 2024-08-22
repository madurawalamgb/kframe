<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $items = Customer::all();
        return view('customers.index', compact('items'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        Customer::create($request->all());

        return redirect()->route('customers.index');
    }
    
    public function show(Customer $customer)
    {
        return view('customers.view',compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'type' => 'required|string',
        //     'selections' => 'required|json',
        //     'readonly' => 'required|boolean',
        //     'disabled' => 'required|boolean',
        // ]);

        $customer->update($request->all());

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index');
    }
}