<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\AnimalMilkRecord;
use App\Models\DailyMilkSummary;
use App\Models\Customer;
use App\Models\customersMilkRecord;

class MilkReportController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->date ?? now()->format('Y-m-d');

        // All animals
        $animals = Animal::orderBy('tag_id')->get();

        // Existing animal milk records for selected date
        $animalRecords = AnimalMilkRecord::whereDate('date', $selectedDate)
            ->get()
            ->keyBy('animal_id');

        // Daily summary (farm use, samples, waste)
        $summary = DailyMilkSummary::whereDate('date', $selectedDate)->first();

        // Home delivery sales
        $homeDelivery = customersMilkRecord::with('customer')
            ->whereDate('date', $selectedDate)
            ->where('milk_delivered', 1)
            ->whereHas('customer', fn($q) => $q->where('customer_type', 'Home Delivery'))
            ->get();

        // Bulk delivery sales
        $bulkDelivery = customersMilkRecord::with('customer')
            ->whereDate('date', $selectedDate)
            ->where('milk_delivered', 1)
            ->whereHas('customer', fn($q) => $q->where('customer_type', 'Bulk Delivery'))
            ->get();

        // Bulk pickup sales
        $bulkPickup = customersMilkRecord::with('customer')
            ->whereDate('date', $selectedDate)
            ->where('milk_delivered', 1)
            ->whereHas('customer', fn($q) => $q->where('customer_type', 'Bulk Pick Up'))
            ->get();

        // Totals
        $totalProduced      = $animalRecords->sum('milk_quantity');
        $totalHomeDelivery  = $homeDelivery->sum('day_liter');
        $totalBulkDelivery  = $bulkDelivery->sum('day_liter');
        $totalBulkPickup    = $bulkPickup->sum('day_liter');
        $totalSold          = $totalHomeDelivery + $totalBulkDelivery + $totalBulkPickup;
        $farmUse            = $summary->farm_use ?? 0;
        $samples            = $summary->samples ?? 0;
        $waste              = $summary->waste ?? 0;
        $totalConsumed      = $farmUse + $samples + $waste;
        $netRemaining       = $totalProduced - $totalSold - $totalConsumed;

        // Revenue
        $homeDeliveryRevenue = $homeDelivery->sum(fn($r) => $r->day_liter * $r->price_day_liter);
        $bulkDeliveryRevenue = $bulkDelivery->sum(fn($r) => $r->day_liter * $r->price_day_liter);
        $bulkPickupRevenue   = $bulkPickup->sum(fn($r) => $r->day_liter * $r->price_day_liter);
        $totalRevenue        = $homeDeliveryRevenue + $bulkDeliveryRevenue + $bulkPickupRevenue;

        return view('milk-report', compact(
            'selectedDate',
            'animals',
            'animalRecords',
            'summary',
            'homeDelivery',
            'bulkDelivery',
            'bulkPickup',
            'totalProduced',
            'totalHomeDelivery',
            'totalBulkDelivery',
            'totalBulkPickup',
            'totalSold',
            'farmUse',
            'samples',
            'waste',
            'totalConsumed',
            'netRemaining',
            'homeDeliveryRevenue',
            'bulkDeliveryRevenue',
            'bulkPickupRevenue',
            'totalRevenue'
        ));
    }

    public function saveAnimalMilk(Request $request)
    {
        try {
            $records = $request->records;
            $date    = $request->date;

            foreach ($records as $record) {
                AnimalMilkRecord::updateOrCreate(
                    [
                        'animal_id' => $record['animal_id'],
                        'date'      => $date,
                    ],
                    [
                        'milk_quantity' => $record['milk_quantity'] ?? 0,
                    ]
                );
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('saveAnimalMilk error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    public function saveDailySummary(Request $request)
    {
        try {
            DailyMilkSummary::updateOrCreate(
                ['date' => $request->date],
                [
                    'farm_use' => $request->farm_use ?? 0,
                    'samples'  => $request->samples  ?? 0,
                    'waste'    => $request->waste     ?? 0,
                ]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('saveDailySummary error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
}