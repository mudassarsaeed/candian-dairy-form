<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calf;

class ManageCalfsController extends Controller
{
    public function index()
    {
        $calfs = Calf::orderBy('calf_date', 'desc')->get();
        return view('manage-calfs', compact('calfs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag_number' => 'required|unique:calfs,tag_number',
            'gender'     => 'required',
        ], [
            'tag_number.required' => 'Tag Number is required',
            'tag_number.unique'   => 'Tag Number already exists',
            'gender.required'     => 'Gender is required',
        ]);

        try {
            Calf::create([
                'tag_number'                 => $request->tag_number,
                'calf_date'                  => $request->calf_date                  ?: null,
                'gender'                     => $request->gender,
                'expected_insemination_date' => $request->expected_insemination_date ?: null,
                'expected_delivery_date'     => $request->expected_delivery_date     ?: null,
                'notes'                      => $request->notes,
            ]);

            return redirect('manage-calfs')->with('success', 'Calf added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'tag_number' => 'required',
            'gender'     => 'required',
        ]);

        try {
            $calf = Calf::findOrFail($request->calf_id);
            $calf->update([
                'tag_number'                 => $request->tag_number,
                'calf_date'                  => $request->calf_date                  ?: null,
                'gender'                     => $request->gender,
                'expected_insemination_date' => $request->expected_insemination_date ?: null,
                'expected_delivery_date'     => $request->expected_delivery_date     ?: null,
                'notes'                      => $request->notes,
            ]);

            return redirect('manage-calfs')->with('success', 'Calf updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            Calf::findOrFail($request->calf_id)->delete();
            return redirect('manage-calfs')->with('success', 'Calf deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}