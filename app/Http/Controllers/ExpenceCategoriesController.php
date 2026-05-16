<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{

    expenceCategory,
};
class ExpenceCategoriesController extends Controller
{
    public function addCategory(Request $request){
     
          // validation
          $request->validate([
            'catname'=>'required'
            
        ],[
            'catname.required' => 'Category name is required',
           
        ]);
        //dd($request->all());

    // error handling using try and catch
    try {
         // $user_id = Auth::user()->id;  /// to get current logged in user id
        
            $cat=new expenceCategory;
            $cat->name = $request->catname;
          
            
            if($cat->save()){
                return redirect()->back()->with('success', 'Category name is added successfully');
            }else{
                return redirect()->back()->with('error', 'Category name not added');
            }
        } catch (\Exception $e) {
            return redirect()->url('expenseCategories')->with('error', $e->getMessage());
        }       
            
    }

    public function editCategory($id){

        $cat = expenceCategory::where('id', $id)->first();
        return view('editcategory', compact('cat'));
    }

    public function updatecategory(Request $request){

        
          // validation
          $request->validate([
            'catname'=>'required'
            
        ],[
            'catname.required' => 'Category name is required',
           
        ]);
    // error handling using try and catch
    try {
        
            $id = $request->catid;
 
            $cat=expenceCategory::find($id);
            
            $cat->name = $request->catname;
          
            
            if($cat->update()){
                return redirect('manage-categories')->with('success', 'Category name is updated successfully');
            }else{
                return redirect()->back()->with('error', 'Category name not updated');
            }
        } catch (\Exception $e) {
            return redirect()->url('manage-categories')->with('error', $e->getMessage());
        }       

    }

    public function deleteExpenseCategory(Request $request){

        $id=$request->categoryId;
        try{
           $result=expenceCategory::where('id',$id)->delete();
           if($result){
               return redirect()->back()->with('success', 'Expense category deleted successfully');
           }
           else{
               return redirect()->back()->with('error', 'Expense category not deleted');
           }

           
        }
        catch (\Exception $e) {  

           return redirect()->back()->with('error', $e->getMessage());
        }

    }
}
