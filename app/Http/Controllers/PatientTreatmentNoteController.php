<?php

namespace App\Http\Controllers;

use App\PatientTreatment;
use App\PatientTreatmentNote;
use App\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PatientTreatmentNoteController extends Controller
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
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'template_id' => 'required|integer',
            'appointment_id' => 'nullable|integer',
            'user_id' => 'required|integer',
        ]);

        // Create new PatientTreatmentNote model assign form values then save to DB
        $patientTreatment = new PatientTreatment();
        $patientTreatment->template_id = $request->template_id;
        $patientTreatment->appointment_id = $request->appointment_id;
        $patientTreatment->user_id = $request->user_id;
        $patientTreatment->type = $request->type;
        $patientTreatment->save();

        // Loop over each answer
        if (isset($request->answer) && !empty($request->answer)) {
            foreach ($request->answer as $index => $value) {
                foreach ($value as $i => $v) {
                    // Check if it is checkbox answer
                    if (is_array($v) && !empty($v)) {
                        $answer = '';

                        // Loop over each checkbox answer
                        foreach ($v as $key => $ans) {
                            $answer .= $ans . ", ";
                        }

                        // Create new PatientTreatmentNote model assign form values then save to DB
                        $patientTreatmentNotes = new PatientTreatmentNote();
                        $patientTreatmentNotes->answer = rtrim($answer, ', ');
                        $patientTreatmentNotes->patient_treatment_id = $patientTreatment->id;
                        $patientTreatmentNotes->question_id = $i;
                        $patientTreatmentNotes->section_id = $index;
                        $patientTreatmentNotes->template_id = $request->template_id;
                        $patientTreatmentNotes->user_id = $request->user_id;
                        $patientTreatmentNotes->save();
                    } else {
                        // Create new PatientTreatmentNote model assign form values then save to DB
                        $patientTreatmentNotes = new PatientTreatmentNote();
                        $patientTreatmentNotes->answer = $v;
                        $patientTreatmentNotes->patient_treatment_id = $patientTreatment->id;
                        $patientTreatmentNotes->question_id = $i;
                        $patientTreatmentNotes->section_id = $index;
                        $patientTreatmentNotes->template_id = $request->template_id;
                        $patientTreatmentNotes->user_id = $request->user_id;
                        $patientTreatmentNotes->save();
                    }
                }
            }
        }

        return redirect()->route('patients.show', [$request->user_id, '#notes'])->with('success', 'Patient Treatment Note saved successfully.')->with('newTemplate', true);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PatientTreatmentNote  $patientTreatmentNote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find the model
        $patientTreatmentNote = PatientTreatment::find($id);

        return view('inc.patients.single_patient.notesShow', compact('patientTreatmentNote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PatientTreatmentNote  $patientTreatmentNote
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Find the model
        $patientTreatment = PatientTreatment::with(['user', 'user.appointments', 'template.sections.questions.answers', 'appointment', 'template.sections.questions.answer' => function($q) use($id) {
            $q->where('patient_treatment_id', $id);
        }])->find($id);

        return view('patients.editTemplate', compact('patientTreatment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PatientTreatmentNote  $patientTreatmentNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate form data
        $request->validate([
            'appointment_id' => 'nullable|integer',
        ]);

        $patientTreatment = PatientTreatment::find($id);
        if (!$patientTreatment) return redirect()->back()->with('success', 'Patient Treatment not found.');
        $notes = $patientTreatment->notes->pluck('id');
        // Loop over each answer
        if (isset($request->answer) && !empty($request->answer)) {
            foreach ($request->answer as $index => $value) {
                foreach ($value as $i => $v) {
                    $answer = $v;
                    // Check if it is checkbox answer
                    if (is_array($v) && !empty($v)) {
                        $answer = '';
                        // Loop over each checkbox answer
                        foreach ($v as $key => $ans) {
                            $answer .= $ans . ", ";
                        }
                    }
                    $patientTreatmentNotes = PatientTreatmentNote::
                                            where('patient_treatment_id', $patientTreatment->id)
                                            ->where('question_id', $i)
                                            ->where('section_id', $index)
                                            ->first();

                    $tempNotes = [];
                    foreach ($notes as $note) {
                        if ($patientTreatmentNotes->id != $note) {
                            array_push($tempNotes, $note);
                        }
                    }
                    $notes = $tempNotes;

                    if (!$patientTreatmentNotes) {
                        // Create new PatientTreatmentNote model assign form values then save to DB
                        $patientTreatmentNotes = new PatientTreatmentNote();
                        $patientTreatmentNotes->answer = rtrim($answer, ', ');
                        $patientTreatmentNotes->patient_treatment_id = $patientTreatment->id;
                        $patientTreatmentNotes->question_id = $i;
                        $patientTreatmentNotes->section_id = $index;
                        $patientTreatmentNotes->template_id = $request->template_id;
                        $patientTreatmentNotes->user_id = $request->user_id;
                        $patientTreatmentNotes->save();

                    } else {
                        $patientTreatmentNotes->answer = rtrim($answer, ', ');
                        $patientTreatmentNotes->update();
                    }
                }
            }
        }
        if (count($notes) > 0) {
            foreach ($notes as $note) {
                $patientTreatmentNotes = PatientTreatmentNote::find($note);
                $patientTreatmentNotes->answer = '';
                $patientTreatmentNotes->update();
            }
        }

        $patientTreatment->appointment_id = $request->appointment_id;
        $patientTreatment->update();

        return redirect()->route('patient_treatment_notes.show', $id)->with('success', 'Patient Treatment Note updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PatientTreatmentNote  $patientTreatmentNote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get the model & remove it
        $treatment = PatientTreatment::find($id);
        if($treatment) {
            PatientTreatment::destroy($id);
            return redirect()->route('patients.show', [$treatment->user_id, '#notes'])->with('success', 'Patient Treatment Note removed successfully.')->with('newTemplate', true);
        }
        return redirect()->back()->with('success', 'Patient Treatment Note removed successfully.')->with('newTemplate', true);
    }

    public function sections(Request $request, $id)
    {
        if(!$request->ajax()) return '';
        $patientTreatment = PatientTreatment::with(['user', 'user.appointments', 'template.sections.questions.answers', 'appointment', 'template.sections.questions.answer' => function($q) use($id) {
            $q->where('patient_treatment_id', $id);
        }])->find($id);
        return response()->json(['success' => true, 'message' => 'Success', 'sections' => $patientTreatment->template->sections]);

    }
}
