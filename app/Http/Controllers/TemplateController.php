<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Section;
use App\Template;
use App\Question;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all resources
        if (auth()->user()->role_id == 3) {
            $templates = Template::where('user_id', auth()->id())->get();
        } else {
            $templates = Template::all();
        }

        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Show the form for creating a new resource
        return view('templates.create');
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
            'title' => 'required|string|max:255',
            'print_title' => 'nullable|string|max:255',
            'sections_title.*' => 'required|string|max:255',
            /*'question_title.*' => 'required|string|max:255',
            'type.*' => 'required|string|max:255',
            'answer.*' => 'nullable|string|max:65535',*/
        ]);

        // Create a new Template model instance assign form values & save to DB
        $template = new Template();
        $template->title = $request->title;
        $template->print_title = (isset($request->print_title)) ?  $request->print_title: $request->title;
        $template->is_show_patients_address = (isset($request->is_show_patients_address)) ?  true: false;
        $template->is_show_patients_dob = (isset($request->is_show_patients_dob)) ?  true: false;
        $template->is_show_patients_nhs_number = (isset($request->is_show_patients_nhs_number)) ?  true: false;
        $template->is_show_patients_referral_source = (isset($request->is_show_patients_referral_source)) ?  true: false;
        $template->is_show_patients_occupation = (isset($request->is_show_patients_occupation)) ?  true: false;
        $template->user_id = auth()->id();
        $template->save();

        // Check if there is any section
        if (isset($request->sections_title)  && !empty($request->sections_title)) {
            // Loop over each section
            foreach ($request->sections_title as $index => $sectionsTitle) {
                // Create a new Section model instance assign form values & save to DB
                $section = new Section();
                $section->title = $sectionsTitle;
                $section->template_id = $template->id;
                $section->save();

                // Check if section has question
                if (isset($request->question_title[$index])  && !empty($request->question_title[$index])) {
                    // Loop over each question under this section
                    foreach ($request->question_title[$index] as $i => $questionTitle) {
                        // Create a new Question model instance assign form values & save to DB
                        $question = new Question();
                        $question->title = $questionTitle;
                        $question->type = $request->type[$index][$i];
                        $question->section_id = $section->id;
                        $question->template_id = $template->id;
                        $question->save();

                        // Check if question has answer
                        if (isset($request->answer[$index][$i]) && !empty($request->answer[$index][$i])) {
                            // Loop over each answer under this question
                            foreach ($request->answer[$index][$i] as $key => $ans) {
                                if ($ans) {
                                    // Create a new Answer model instance assign form values & save to DB
                                    $answer = new Answer();
                                    $answer->answer = $ans;
                                    $answer->question_id = $question->id;
                                    $answer->section_id = $section->id;
                                    $answer->template_id = $template->id;
                                    $answer->save();
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Template created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function show(Template $template)
    {
        // Find the Template model
        $template = Template::with('sections.questions.answers')->find($template->id);

        return response()->json(['status' => 200, 'data' => $template]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function edit(Template $template)
    {
        // Find the Template model
        $template = Template::find($template->id);

        // Show the form for editing the specified resource
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Template  $template
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Template $template)
    {
        // Validate form data
        $request->validate([
            'title' => 'required|string|max:255',
            'print_title' => 'nullable|string|max:255',
            'sections_title.*' => 'required|string|max:255',
            /*'question_title.*' => 'required|string|max:255',
            'type.*' => 'required|string|max:255',
            'answer.*' => 'nullable|string|max:65535',*/
        ]);

        // Find the Template model instance assign form values & save to DB
        $template = Template::find($template->id);
        $template->title = $request->title;
        $template->print_title = (isset($request->print_title)) ?  $request->print_title: $request->title;
        $template->is_show_patients_address = (isset($request->is_show_patients_address)) ?  true: false;
        $template->is_show_patients_dob = (isset($request->is_show_patients_dob)) ?  true: false;
        $template->is_show_patients_nhs_number = (isset($request->is_show_patients_nhs_number)) ?  true: false;
        $template->is_show_patients_referral_source = (isset($request->is_show_patients_referral_source)) ?  true: false;
        $template->is_show_patients_occupation = (isset($request->is_show_patients_occupation)) ?  true: false;
        $template->save();

        // Check if there is any section
        if (isset($request->sections_title) && !empty($request->sections_title)) {
            // Loop over each section
            foreach ($request->sections_title as $index => $sectionsTitle) {
                // If existing section then update it otherwise create new
                if (isset($request->section_id[$index])) {
                    // Find Section model instance assign form values & save to DB
                    $section = Section::find($request->section_id[$index]);
                    $section->title = $sectionsTitle;
                    $section->save();
                } else {
                    // Create a new Section model instance assign form values & save to DB
                    $section = new Section();
                    $section->title = $sectionsTitle;
                    $section->template_id = $template->id;
                    $section->save();
                }

                // Check if section has question
                if (isset($request->question_title[$index]) && !empty($request->question_title[$index])) {
                    // Loop over each question under this section
                    foreach ($request->question_title[$index] as $i => $questionTitle) {
                        // If existing question then update it otherwise create new
                        if (isset($request->question_id[$index][$i])) {
                            // Find Question model instance assign form values & save to DB
                            $question = Question::find($request->question_id[$index][$i]);
                            $question->title = $questionTitle;
                            $question->type = $request->type[$index][$i];
                            $question->save();
                        } else {
                            // Create a new Question model instance assign form values & save to DB
                            $question = new Question();
                            $question->title = $questionTitle;
                            $question->type = $request->type[$index][$i];
                            $question->section_id = $section->id;
                            $question->template_id = $template->id;
                            $question->save();
                        }

                        // Check if question has answer
                        if (isset($request->answer[$index][$i])  && !empty($request->answer[$index][$i])) {
                            // Loop over each answer under this question
                            foreach ($request->answer[$index][$i] as $key => $ans) {
                                if ($ans) {
                                    // If existing answer then update it otherwise create new
                                    if (isset($request->answer_id[$index][$i][$key])) {
                                        // Find Answer model instance assign form values & save to DB
                                        $answer = Answer::find($request->answer_id[$index][$i][$key]);
                                        $answer->answer = $ans;
                                        $answer->save();
                                    } else {
                                        // Create a new Answer model instance assign form values & save to DB
                                        $answer = new Answer();
                                        $answer->answer = $ans;
                                        $answer->question_id = $question->id;
                                        $answer->section_id = $section->id;
                                        $answer->template_id = $template->id;
                                        $answer->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Template updated successfully.');
    }

    /**
 * Remove the specified resource from storage.
 *
 * @param  \App\Template  $template
 * @return \Illuminate\Http\Response
 */
    public function destroy(Template $template)
    {
        // Remove the specified resource from storage
        Template::destroy($template->id);

        return redirect()->back()->with('success', 'Template deleted successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroySection($id)
    {
        // Remove the specified resource from storage
        Section::destroy($id);

        return response()->json(['status' => 200, 'msg' => 'Section deleted successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyQuestion($id)
    {
        // Remove the specified resource from storage
        Question::destroy($id);

        return response()->json(['status' => 200, 'msg' => 'Question deleted successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAnswer($id)
    {
        // Remove the specified resource from storage
        Answer::destroy($id);

        return response()->json(['status' => 200, 'msg' => 'Answer deleted successfully.']);
    }
}
