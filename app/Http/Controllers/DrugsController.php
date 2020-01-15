<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Drugs\CreateDrugs;
use App\Http\Requests\Drugs\UpdateDrugs;
use App\Drug;
use App\Medication;

class DrugsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(createDrugs $request)
    {
        $this->validate($request, [
            'prescription_id' => 'required|integer',
            'medication_id' => 'required|integer',
            'dosage' => 'required|string',
            'duration_quantity' => 'required|string',
            'dispensed_by' => 'sometimes|nullable|string'
        ]);
        Drug::create([
            'prescription_id' => $request->prescription_id,
            'medication_id' => $request->medication_id,
            'dosage' => $request->dosage,
            'duration_quantity' => $request->duration_quantity,
            'dispensed_by' => $request->dispensed_by,
            'user_id' => $request->user_id,
            'creator_id' => auth()->user()->id
        ]);

        return redirect()->back()->with('Prescription Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $drug = Drug::findOrfail($id);
        
        $medications = Medication::all();

        return view('drugs.edit', compact('drug', 'medications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDrugs $request, $id)
    {
        $drug = Drug::findOrfail($id);

        $drug->medication_id = $request->medication_id;
        $drug->dosage = $request->dosage;
        $drug->duration_quantity = $request->duration_quantity;
        $drug->dispensed_by = $request->dispensed_by;
        $drug->user_id = $request->user_id;
        $drug->creator_id = auth()->user()->id;
        
        $drug->save();

        return redirect()->route('prescriptions.show', $request->medication_id)->with('Prescription Update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $drug = Drug::findOrfail($id);

        $drug->delete();

        return redirect()->route('prescriptions.show', $request->medication_id)->with('Prescription Update Successfully.');
    }
}
