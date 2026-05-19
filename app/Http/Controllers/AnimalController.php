<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;

class AnimalController extends Controller
{
    public function index()
    {
        $animals = Animal::orderBy('tag_id')->get();

        $totalCows    = Animal::where('animal_type', 'Cow')->count();
        $totalHeifers = Animal::where('animal_type', 'Heifer')->count();
        $totalCalves  = Animal::where('animal_type', 'Calf')->count();
        $milking      = Animal::where('status', 'Milking')->count();
        $pregnant     = Animal::where('status', 'Pregnant')->count();
        $dry          = Animal::where('status', 'Dry')->count();

        return view('manage-animals', compact(
            'animals',
            'totalCows',
            'totalHeifers',
            'totalCalves',
            'milking',
            'pregnant',
            'dry'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tag_id'      => 'required|unique:animals,tag_id',
            'animal_type' => 'required',
            'gender'      => 'required',
            'status'      => 'required',
        ], [
            'tag_id.required'      => 'Tag ID is required',
            'tag_id.unique'        => 'Tag ID already exists',
            'animal_type.required' => 'Animal Type is required',
            'gender.required'      => 'Gender is required',
            'status.required'      => 'Status is required',
        ]);

        try {
            Animal::create([
                'tag_id'               => $request->tag_id,
                'animal_type'          => $request->animal_type,
                'gender'               => $request->gender,
                'timer'                => $request->timer,
                'insemination_date'    => $request->insemination_date ?: null,
                'semen_type'           => $request->semen_type,
                'exp_insemination_date'=> $request->exp_insemination_date ?: null,
                'confirmation_date'    => $request->confirmation_date,
                'status'               => $request->status,
                'calf_date'            => $request->calf_date ?: null,
                'date_of_birth'        => $request->date_of_birth ?: null,
                'notes'                => $request->notes,
            ]);

            return redirect('manage-animals')->with('success', 'Animal added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'tag_id'      => 'required',
            'animal_type' => 'required',
            'status'      => 'required',
        ]);

        try {
            $animal = Animal::findOrFail($request->animal_id);
            $animal->update([
                'tag_id'               => $request->tag_id,
                'animal_type'          => $request->animal_type,
                'gender'               => $request->gender,
                'timer'                => $request->timer,
                'insemination_date'    => $request->insemination_date ?: null,
                'semen_type'           => $request->semen_type,
                'exp_insemination_date'=> $request->exp_insemination_date ?: null,
                'confirmation_date'    => $request->confirmation_date,
                'status'               => $request->status,
                'calf_date'            => $request->calf_date ?: null,
                'date_of_birth'        => $request->date_of_birth ?: null,
                'notes'                => $request->notes,
            ]);

            return redirect('manage-animals')->with('success', 'Animal updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            Animal::findOrFail($request->animal_id)->delete();
            return redirect('manage-animals')->with('success', 'Animal deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}