<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transport;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customer::latest()->get();
        $transport = Transport::all();

        return view('dashboard', compact('customer', 'transport'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'nullable|string|max:11',
            'description' => 'nullable|string|max:1000'
        ]);

        Customer::create($validated);
        return redirect()->back()->with('success', 'Customer created successfully!');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'phone' => 'required|string|max:11',
            'description' => 'nullable|string|max:1000'
        ]);

        $customer->update($validated);
        return redirect()->back()->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }
}
