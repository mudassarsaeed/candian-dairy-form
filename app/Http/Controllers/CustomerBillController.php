<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerBill;
use App\Models\customersMilkRecord;
use Carbon\Carbon;

class CustomerBillController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->month ?? now()->format('Y-m');
        $customers     = Customer::orderBy('name')->get();

        // Generate/update bills for all customers for selected month
        $bills = [];
        foreach ($customers as $customer) {
            $records = customersMilkRecord::where('customer_id', $customer->id)
                ->where('milk_delivered', 1)
                ->whereYear('date', substr($selectedMonth, 0, 4))
                ->whereMonth('date', substr($selectedMonth, 5, 2))
                ->get();

            $totalLiters = $records->sum('day_liter');
            $totalAmount = $records->sum(fn($r) => $r->day_liter * $r->price_day_liter);
            $totalDays   = $records->count();

            // Get existing bill status
            $bill = CustomerBill::where('customer_id', $customer->id)
                ->where('month', $selectedMonth)
                ->first();

            $bills[$customer->id] = [
                'customer'     => $customer,
                'total_liters' => $totalLiters,
                'total_amount' => $totalAmount,
                'total_days'   => $totalDays,
                'status'       => $bill->status ?? 'Unpaid',
                'paid_date'    => $bill->paid_date ?? null,
                'bill_id'      => $bill->id ?? null,
            ];
        }

        return view('customer-bills', compact('bills', 'selectedMonth', 'customers'));
    }

    public function receipt(Request $request)
    {
        $customerId    = $request->customer_id;
        $selectedMonth = $request->month ?? now()->format('Y-m');

        $customer = Customer::findOrFail($customerId);

        $records = customersMilkRecord::where('customer_id', $customerId)
            ->where('milk_delivered', 1)
            ->whereYear('date', substr($selectedMonth, 0, 4))
            ->whereMonth('date', substr($selectedMonth, 5, 2))
            ->orderBy('date')
            ->get();

        $totalLiters = $records->sum('day_liter');
        $totalAmount = $records->sum(fn($r) => $r->day_liter * $r->price_day_liter);
        $totalDays   = $records->count();

        $bill = CustomerBill::where('customer_id', $customerId)
            ->where('month', $selectedMonth)
            ->first();

        return view('customer-receipt', compact(
            'customer',
            'records',
            'totalLiters',
            'totalAmount',
            'totalDays',
            'selectedMonth',
            'bill'
        ));
    }

    public function updateStatus(Request $request)
    {
        try {
            $bill = CustomerBill::updateOrCreate(
                [
                    'customer_id' => $request->customer_id,
                    'month'       => $request->month,
                ],
                [
                    'total_liters' => $request->total_liters,
                    'total_amount' => $request->total_amount,
                    'total_days'   => $request->total_days,
                    'status'       => $request->status,
                    'paid_date'    => $request->status === 'Paid' ? now()->format('Y-m-d') : null,
                ]
            );

            return response()->json(['success' => true, 'status' => $bill->status]);
        } catch (\Exception $e) {
            \Log::error('updateStatus error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
}