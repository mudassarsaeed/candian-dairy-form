<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Expenses;
use App\Models\{

    expenceCategory,
};
class ExpensesController extends Controller
{
    public function allExpenses(){
        $expensecategories = expenceCategory::get();
        $expenses = Expenses::get();
        return view('manage-expenses', compact('expenses','expensecategories'));
    }
    
 public function creatExpense(Request $request)
{
    $request->validate([
        'cat'        => 'required',
        'order_date' => 'required',
        'arival_date'=> 'required',
        'enddate'    => 'required',
        'unit'       => 'required',
        'unit_price' => 'required',
        'toatl'      => 'required',
        // validate other_category only if Other is selected
        'other_category' => 'required_if:cat,other',
    ], [
        'cat.required'              => 'Category is required',
        'order_date.required'       => 'Order Date is required',
        'arival_date.required'      => 'Arrival Date is required',
        'enddate.required'          => 'Ending Date is required',
        'unit.required'             => 'Unit is required',
        'unit_price.required'       => 'Unit Price is required',
        'toatl.required'            => 'Total is required',
        'other_category.required_if'=> 'Please specify the other category',
    ]);

    try {
        $user_id = Auth::user()->id;
        $receiptfileName = null;

        if ($request->hasFile('file')) {
            $file            = $request->file('file');
            $receiptfileName = time().'.'.$file->getClientOriginalExtension();
            $path            = public_path('uploads/receipt');
            $file->move($path, $receiptfileName);
        }

        $expense                  = new Expenses;
        $expense->cat_id          = $request->cat === 'other' ? null : $request->cat;
        $expense->other_category  = $request->cat === 'other' ? $request->other_category : null;
        $expense->order_date      = $request->order_date;
        $expense->arrival_date    = $request->arival_date;
        $expense->ending_date     = $request->enddate;
        $expense->unit            = $request->unit;
        $expense->number_bags_bails = $request->nbbs;
        $expense->unit_price      = $request->unit_price;
        $expense->total           = $request->toatl;
        $expense->receipt         = $receiptfileName;

        if ($expense->save()) {
            return redirect('manage-expenses')->with('success', 'Expense added successfully');
        } else {
            return redirect()->back()->with('error', 'Expense not added');
        }
    } catch (\Exception $e) {
        return redirect()->back()->with('error', $e->getMessage());
    }
}

    public function editCompany($id){

        $company = Expenses::where('id', $id)->first();
        return view('editcompany', compact('company'));
    }

    public function deleteCompany(Request $request){


        $id=$request->commpanyId;
        try{
           $result=Expenses::where('id',$id)->delete();
           if($result){
               return redirect()->back()->with('success', 'Company deleted successfully');
           }
           else{
               return redirect()->back()->with('error', 'Company not deleted');           }

           
        }
        catch (\Exception $e) {  

           return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateCompany(Request $request){
        //   dd($request->all());
           // validation
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'abnNumber'=>'required',
            'phone'=>'required',
            'address'=>'required'
        ],[
            'name.required' => 'Company name is required',
            'email.required' => 'Email is required',
            'abnNumber.required' => 'ABN number is required',
            'phone.required' => 'Phone  is required',
            'address.required' => 'Address is required',
        ]);
    // error handling using try and catch
    try {

           $path = '';
            if( $request->hasFile('file') ) {
                $file = $request->file('file');
                // Get the Image Name
                $logofileName = time().'.'.$file->getClientOriginalExtension();
                // Set the Filepath 
                $path = public_path('uploads/companylogo') ;
                // Move the file to the upload Folder
                $file = $file->move($path, $logofileName);
            }
        
            $id = $request->companyId;
            $company=Expenses::find($id);
            
            $company->name = $request->name;
            $company->email = $request->email;
            $company->abn_number = $request->abnNumber;
            $company->phone = $request->phone;
            $company->Address = $request->address;
            if( $request->hasFile('file') ) {
                $company->logoimage  = $logofileName;
            }
            
            if($company->update()){
                return redirect('manage-componies')->with('success', 'Company updated successfully');
            }else{
                return redirect()->back()->with('error', 'Company not updated');
            }
        } catch (\Exception $e) {
           // dd($e->getMessage());
             return redirect()->url('manage-componies')->with('error', $e->getMessage());
        }       
    }

    //  public function getCompany()
    // {
    //     $expensecategories = expenceCategory::get();
  
    //     //dd($assignVehicle);
    //     return view('manage-expenses', compact('expensecategories'));


    // }
}
