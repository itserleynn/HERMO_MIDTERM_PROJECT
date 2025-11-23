<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transport;

class TransportController extends Controller
{
    public function index()
    {
        $customer = Customer::latest()->get();
        $transport = Transport::all();

        return view('transports', compact('transport', 'customer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|max:10',
            'description' => 'nullable|string|max:1000'
        ]);

        Transport::create($validated);
        return redirect()->back()->with('success', 'Transportion added successfully.');
    }

    public function update(Request $request, Transport $transport)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|max:10',
            'description' => 'nullable|string|max:1000'
        ]);

        $transport->update($validated);
        return redirect()->back()->with('success', 'Transportion updated successfully.');
    }

    public function destroy(Transport $transport)
    {
        $transport->delete();
        return redirect()->back()->with('succes', 'Transportion deleted successfully.');
    }
}
