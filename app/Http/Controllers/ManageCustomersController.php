<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{
Customer,
customersMilkRecord,
};
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
class ManageCustomersController extends Controller
{
     
  public function AllCustomers(){
        $customers =  Customer::get();
        return view('manage-customers', compact('customers'));
    }
    
    public function creatCustomer(Request $request){
    //dd($request->all());

         // validation
        $request->validate([
            'name'=>'required',
             'address'=>'required',
              'customer_type' => 'required'
            // 'phone' => 'required',
            // 'price' => 'required',
            // 'liter_per_day' => 'required'
        
        ],[
            'name.required' => 'Customer Name is required',
             'address.required' => 'Customer Address is required',
             'customer_type.required' => 'Customer Type is required',
            // 'phone.required' => 'Customer Phone is required',
            // 'price.required' => 'Customer Price is required',
            // 'liter_per_day.required' => 'Liter Per Day is required',
            
        ]);
    // error handling using try and catch
    try {
            $user_id = Auth::user()->id;  /// to get current logged in user id
            
            $customer=new Customer;
            $customer->name = $request->name ;
            $customer->email = $request->email ;
            $customer->address = $request->address ;
            $customer->phone = $request->phone ;
            $customer->whatsapp = $request->whatsapp ;
            $customer->price_liter = $request->price ;
            $customer->liter_per_day = $request->liter_per_day ;
            $customer->customer_type = $request->customer_type;  // add this
           
            if($customer->save()){
                return redirect('manage-customers')->with('success', 'Customer is added successfully');
            }else{
                return redirect()->back()->with('error', 'Customer not added');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }       
    }
public function AllCustomersMilkRecord(Request $request)
{
    $selectedDate = $request->date ?? now()->format('Y-m-d');
    $customers = Customer::all();

    // Use whereDate() instead of where() to handle datetime columns
    $existingRecords = customersMilkRecord::whereDate('date', $selectedDate)
        ->pluck('milk_delivered', 'customer_id');

    return view('customer-milk-record', compact('customers', 'selectedDate', 'existingRecords'));
}

public function saveDayDeliveries(Request $request)
{
    try {
        $deliveries = $request->deliveries;
        $date = $request->date;

        if (!$deliveries || !is_array($deliveries)) {
            return response()->json(['success' => false, 'message' => 'No data received'], 400);
        }

        foreach ($deliveries as $delivery) {
            $customer = Customer::find($delivery['customer_id']);

            if (!$customer) continue;

            $record = customersMilkRecord::where('customer_id', $delivery['customer_id'])
                        ->whereDate('date', $date)
                        ->first();

            if ($record) {
                $record->update([
                    'milk_delivered'  => $delivery['milk_delivered'],
                    'day_liter'       => $customer->liter_per_day,
                    'price_day_liter' => $customer->price_liter,
                ]);
            } else {
                customersMilkRecord::create([
                    'customer_id'     => $delivery['customer_id'],
                    'date'            => $date,
                    'milk_delivered'  => $delivery['milk_delivered'],
                    'day_liter'       => $customer->liter_per_day,
                    'price_day_liter' => $customer->price_liter,
                ]);
            }
        }

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        \Log::error('saveDayDeliveries error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.'
        ], 500);
    }
}
// New method to update milk delivery status
public function updateMilkDelivery(Request $request)
{
    customersMilkRecord::updateOrCreate(
    [
        'customer_id' => $delivery['customer_id'],  // find by customer
        'date'        => $date,                      // AND date together
    ],
    [
        'milk_delivered'  => $delivery['milk_delivered'],
        'day_liter'       => $customer->liter_per_day,
        'price_day_liter' => $customer->price_liter,
    ]
    );
    $record = customersMilkRecord::findOrFail($request->record_id);
    $record->milk_delivered = $request->milk_delivered;
    $record->save();

    return response()->json(['success' => true]);
}

     public function createMilkRecord(Request $request){
    //dd($request->all());

         // validation
        $request->validate([
            'customer_id'=>'required',
             'milk_quantity'=>'required',
             'date'=>'required'
       
        ],[
            'customer_id.required' => 'Customer is required',
             'milk_quantity.required' => 'Milk Quantity is required',
            'date.required' => 'Date is required',

            
        ]);
    // error handling using try and catch
    try {
            $user_id = Auth::user()->id;  /// to get current logged in user id
            
            $customer=new customersMilkRecord;
            $customer->customer_id = $request->customer_id ;
            $customer->day_liter = $request->milk_quantity ;
            $customer->price_day_liter = $request->price_per_liter ;
            $customer->date = $request->date ;           
            if($customer->save()){
                return redirect('customer-milk-records')->with('success', 'Customer milk record is added successfully');
            }else{
                return redirect()->back()->with('error', 'Customer milk record not added');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }       
    }
   
     public function importCustomers(Request $request)
        {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv',
            ], [
                'file.required' => 'Please select a file to import',
                'file.mimes'    => 'Only xlsx, xls, csv files are allowed',
            ]);

            try {
                Excel::import(new CustomersImport, $request->file('file'));
                return redirect('manage-customers')->with('success', 'Customers imported successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
            }
        }
            
}
