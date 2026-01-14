<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Transport;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('transport');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                    ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('transport_filter') && $request->transport_filter != '') {
            $query->where('transport_id', $request->transport_filter);
        }

        $customer = $query->latest()->get();
        $transport = Transport::all();

        return view('dashboard', compact('customer', 'transport'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'transport_id' => 'nullable|exists:transports,id',
            'phone' => 'nullable|string|max:11',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('customer_photo', 'public');
        $validated['photo'] = $photoPath;
    }

        Customer::create($validated);
        return redirect()->back()->with('success', 'Customer created successfully!');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'transport_id' => 'nullable|exists:transports,id',
            'phone' => 'required|string|max:11',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }

            $photoPath = $request->file('photo')->store('customer_photo', 'public');
            $validated['photo'] = $photoPath;
        }

        $customer->update($validated);
        return redirect()->back()->with('success', 'Customer updated successfully!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.trash')->with('success', 'Customer deleted successfully!');
    }

    public function trash()
    {
        $customers = Customer::onlyTrashed()->with('transport')->latest('deleted_at')->get();
        $transport = Transport::all();

        return view('trash', compact('customers', 'transport'));
    }

    public function restore ($id)
    {
        $customers = Customer::withTrashed()->findOrFail($id);
        $customers->restore();

        return redirect()->route('dashboard')->with('success', 'Customer restored successfully');
    }

    public function forceDelete($id)
    {
        $customers = Customer::withTrashed()->findOrFail($id);

        if ($customers->photo) {
            Storage::disk('public')->delete($customers->photo);
        }

        $customers->forceDelete();

        return redirect()->route('customers.trash')->with('success', 'Customer permanently deleted successfully');
    }

    public function export(Request $request)
    {
        $query = Customer::with('transport');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                    ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('transport_filter') && request->transport_filter != '') {
            $query->where('transport_id', $request->transport_filter);
        }

        $customers = $query->latest()->get();

        $filename = 'customers_report_' . date('Y-m-d_His') . '.pdf';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Customer Report</title>
            <style>
                body {
                    font-family: "Helvetica", Arial, sans-serif;
                    background: #f9fafb;
                    margin: 0;
                    padding: 30px;
                    color: #1f2937;
                }

                .container {
                    max-width: 1100px;
                    margin: auto;
                    background: #ffffff;
                    padding: 32px;
                    border-radius: 12px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
                }

                .header {
                    text-align: center;
                    margin-bottom: 25px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 28px;
                    color: #111827;
                    letter-spacing: 0.5px;
                }

                .header p {
                    margin-top: 6px;
                    font-size: 14px;
                    color: #6b7280;
                }

                .divider {
                    height: 2px;
                    background: #d1d5db;
                    margin: 20px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                }

                th {
                    background: #1e3a8a; /* dark blue */
                    color: #ffffff;
                    padding: 12px 10px;
                    text-align: left;
                }

                td {
                    padding: 10px;
                    border-bottom: 1px solid #e5e7eb;
                    vertical-align: top;
                }

                tr:nth-child(even) {
                    background: #f3f4f6;
                }

                .badge {
                    display: inline-block;
                    padding: 4px 8px;
                    font-size: 12px;
                    border-radius: 12px;
                    background: #dbeafe; /* light blue */
                    color: #1e3a8a; /* dark blue */
                    font-weight: 600;
                }

                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 13px;
                    color: #6b7280;
                }

                @media print {
                    body {
                        background: white;
                        padding: 0;
                    }
                    .container {
                        border-radius: 0;
                        box-shadow: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">

                <div class="header">
                    <h1>Customer Report</h1>
                    <p>
                        Exported on ' . date('F d, Y \\a\\t h:i A') . '<br>
                        Total Records: ' . $customers->count() . '
                    </p>
                </div>

                <div class="divider"></div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Photo</th>
                            <th>Full Name</th>
                            <th>Location</th>
                            <th>Transportation</th>
                            <th>Phone</th>
                            <th>Description</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody>';
        $number = 1;
        foreach ($customers as $cust) {
            $photo = $cust->photo ? '<img src="' . Storage::url($cust->photo) . '" alt="' . htmlspecialchars($cust->full_name) . '" style="height:40px;width:40px;border-radius:50%;object-fit:cover;">' 
                : '<div style=" height:40px;width:40px;border-radius:50%;background:#dbeafe;color:#1e3a8a;font-weight:600;font-size:14px;text-align:center;line-height:40px;vertical-align:middle;">' . strtoupper(substr($cust->full_name,0,2)) . '</div>';

            $html .= '<tr>
                <td>' . $number++ . '</td>
                <td>' . $photo . '</td>
                <td>' . htmlspecialchars($cust->full_name) . '</td>
                <td>' . htmlspecialchars($cust->location) . '</td>
                <td><span class="badge">' . ($cust->transport?->name ?? 'N/A') . '</span></td>
                <td>' . htmlspecialchars($cust->phone ?? '-') . '</td>
                <td>' . htmlspecialchars($cust->description ?? '-') . '</td>
                <td>' . $cust->created_at->format('Y-m-d H:i:s') . '</td>
            </tr>';
        }

        $html .= '</tbody>
                </table>

                <div class="footer">
                    Total Customers: ' . $customers->count() . '<br/>
                    © ' . date('Y') . ' Customer Management System. All rights reserved.
                </div>
            </div>
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
