<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\customersMilkRecord;
use App\Models\Expenses;
class IncomeSheetController extends Controller
{
    public function index()
    {
        return view('manage-incomesheet');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $fromDate = $request->from_date;
        $toDate   = $request->to_date;

        // Total Sales from customers_milk_records
        $salesRecords = customersMilkRecord::with('customer')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->where('milk_delivered', 1)
            ->get();

        $totalSales = $salesRecords->sum(function ($record) {
            return $record->day_liter * $record->price_day_liter;
        });

        // Total Expenses
        $expenseRecords = expenses::whereBetween('order_date', [$fromDate, $toDate])->get();
        $totalExpenses  = $expenseRecords->sum('total');

        // Net Income
        $netIncome = $totalSales - $totalExpenses;

        return view('manage-incomesheet', compact(
            'salesRecords',
            'expenseRecords',
            'totalSales',
            'totalExpenses',
            'netIncome',
            'fromDate',
            'toDate'
        ));
    }
}