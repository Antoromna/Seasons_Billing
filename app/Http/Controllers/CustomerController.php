<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerOpeningBalance;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $customers = Customer::latest()->get();

    return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|digits_between:10,15',
            'address' => 'required|string',

            'email' => 'nullable|email|max:255',
            'landline' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',

            'opening_balance' => 'nullable|numeric|min:0',
            'status' => 'nullable|boolean',
        ]);

        Customer::create([
            'name' => $validated['name'],
            'mobile_no' => $validated['mobile_no'],
            'address' => $validated['address'],

            'email' => $validated['email'] ?? null,
            'landline' => $validated['landline'] ?? null,
            'gstin' => $validated['gstin'] ?? null,
            'state' => $validated['state'] ?? null,

            'opening_balance' => $validated['opening_balance'] ?? 0,
            'status' => $validated['status'] ?? 1,
        ]);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|digits_between:10,15',
            'address' => 'required|string',

            'email' => 'nullable|email|max:255',
            'landline' => 'nullable|string|max:20',
            'gstin' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',

            'opening_balance' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
    public function storeOpeningBalance(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'opening_balance' => 'required|numeric|min:1',
    ]);

    CustomerOpeningBalance::create([
        'customer_id' => $request->customer_id,
        'amount' => $request->opening_balance,
        'remarks' => $request->remarks,
    ]);

    return back()->with(
        'success',
        'Opening Balance Added Successfully'
    );
}
}
