<?php

namespace App\Http\Controllers;

use App\Questionnaire;
use App\QuestionnaireAnswer;
use App\QuestionnaireQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionnaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        // Get all resources
        $questionnaires = Questionnaire::all();

        return view('questionnaires.index', compact('questionnaires'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        // Show the form for creating a new resource
        return view('questionnaires.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'title' => 'required|string|max:255',
            'question_title.*' => 'required|string|max:255',
            'yes_more_info' => 'nullable|string',
            'no_more_info' => 'nullable|string',
        ]);

        // Create a new Template model instance assign form values & save to DB
        $questionnaires = new Questionnaire();
        $questionnaires->title = $request->title;
        $questionnaires->description = $request->description;
        $questionnaires->generic_comment = $request->generic_comment;
        $questionnaires->yes_more_info = $request->yes_more_info;
        $questionnaires->no_more_info = $request->no_more_info;
        $questionnaires->user_id = auth()->id();

        if($request->hasFile('image')) {
            $questionnaires->image = $request->image->store('public/questionnaire_image');
            $questionnaires->image = str_replace("public/", "", $questionnaires->image);
        }
        $questionnaires->save();

        // Check if there is any question
        if (isset($request->question_title)) {
            // Loop over each question
            foreach ($request->question_title as $index => $qTitle) {
                // Create a new Question model instance assign form values & save to DB

                $image = '';
                if($request->hasFile('question_image.'.$index)) {
                    $image = $request->question_image[$index]->store('public/questionnaire_image');
                    $image = str_replace("public/", "", $image);
                }

                $question = new QuestionnaireQuestion();
                $question->title = $qTitle;
                $question->description = $request->question_description[$index];
                $question->image = $image;
                $question->questionnaire_id = $questionnaires->id;
                $question->save();

                // Check if section has question
                if (isset($request->answer_title[$index])) {
                    // Loop over each Answer under this section
                    foreach ($request->answer_title[$index] as $i => $ansTitle) {
                        // Create a new Answer model instance assign form values & save to DB
                        $answer = new QuestionnaireAnswer();
                        $answer->title = $ansTitle;
                        $answer->advice = $request->answer_advice[$index][$i];
                        $answer->questionnaire_question_id = $question->id;
                        $answer->save();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Questionnaire created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Questionnaire $questionnaire)
    {
        // Find the Questionnaire model
        $questionnaire = Questionnaire::with('questions.answers')->find($questionnaire->id);

        return view('questionnaires.show', compact('questionnaire'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Questionnaire $questionnaire)
    {
        // Find the Template model
        $questionnaire = Questionnaire::with('questions.answers')->find($questionnaire->id);
        //dd($questionnaire);

        // Show the form for editing the specified resource
        return view('questionnaires.edit', compact('questionnaire'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Questionnaire  $questionnaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {
        // Validate form data
        $request->validate([
            'title' => 'required|string|max:255',
            'question_title.*' => 'required|string|max:255',
            'yes_more_info' => 'nullable|string',
            'no_more_info' => 'nullable|string',
        ]);

        // Find Questionnaire model instance assign form values & save to DB
        $questionnaires = Questionnaire::find($questionnaire->id);
        $questionnaires->title = $request->title;
        $questionnaires->description = $request->description;
        $questionnaires->generic_comment = $request->generic_comment;
        $questionnaires->yes_more_info = $request->yes_more_info;
        $questionnaires->no_more_info = $request->no_more_info;
        $questionnaires->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $questionnaires->image = $request->image->store('public/questionnaire_image');
            $questionnaires->image = str_replace("public/", "", $questionnaires->image);
        }
        $questionnaires->save();

        // Check if there is any section
        if (isset($request->question_title)) {
            // Loop over each section
            foreach ($request->question_title as $index => $qTitle) {
                // Find Question model instance assign form values & save to DB
                if (isset($request->question_id[$index])) {
                    $question = QuestionnaireQuestion::find($request->question_id[$index]);
                } else {
                    $question = new QuestionnaireQuestion();
                }

                $image = '';
                if($request->hasFile('question_image.'.$index)) {
                    $image = $request->question_image[$index]->store('public/questionnaire_image');
                    $image = str_replace("public/", "", $image);
                }

                $question->title = $qTitle;
                $question->description = $request->question_description[$index];
                $question->image = $image;
                $question->questionnaire_id = $questionnaires->id;
                $question->save();
                //dump($question);

                // Check if question has Answer
                if (isset($request->answer_title[$index])) {
                    // Loop over each Answer under this section
                    foreach ($request->answer_title[$index] as $i => $ansTitle) {
                        // Find Answer model instance assign form values & save to DB
                        if (isset($request->answer_id[$index][$i])) {
                            $answer = QuestionnaireAnswer::find($request->answer_id[$index][$i]);
                        } else {
                            $answer = new QuestionnaireAnswer();
                        }
                        $answer->title = $ansTitle;
                        $answer->advice = $request->answer_advice[$index][$i];
                        $answer->questionnaire_question_id = $question->id;
                        $answer->save();
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Questionnaire created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Questionnaire $questionnaire)
    {
        Questionnaire::destroy($questionnaire->id);

        return redirect()->back()->with('success', 'Questionnaire removed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyRow($type, $id)
    {
        if ($type == 'question') {
            QuestionnaireQuestion::destroy($id);
            return response()->json(['status' => 200, 'msg' => 'Question deleted successfully.']);
        } elseif ($type == 'answer') {
            QuestionnaireAnswer::destroy($id);
            return response()->json(['status' => 200, 'msg' => 'Answer deleted successfully.']);
        }
    }

    /**
     * Import excel file.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request  $request)
    {
        // Validate form data
        /*$rules = array(
            'excel_file' => 'required|mimes:csv,xlsx,xls',
        );

        $validator = validator($request->all(), $rules);

        if ($validator->fails()){
            return response()->json(array('errors'=> $validator->getMessageBag()->toarray()));
        }*/

        try {
            // Checks if the file exists
            if ($request->hasFile('excel_file')) {
                // Get file name with extension
                $fileNameWithExt = $request->file('excel_file')->getClientOriginalName();
                // Get only file name
                $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get only extension
                $extension = $request->file('excel_file')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = $fileName . Str::random(20) . "." . $extension;
                // Directory to upload
                $request->file('excel_file')->storeAs('public/excel/', $fileNameToStore);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        } finally {
            return response()->json([asset('/storage/excel/'.$fileNameToStore), $fileNameToStore]);
        }
    }

    /**
     * Import excel file.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importStore(Request  $request)
    {
        // Validate form data
        $request->validate([
            'data' => 'required',
            'fileName' => 'required|string',
        ]);

        try {
            $data = json_decode($request->data, true);

            if (isset($data['Questionnaires'])) {
                foreach ($data['Questionnaires'] as $questionnaire) {
                    $questionnaires = new Questionnaire();
                    $questionnaires->title = isset($questionnaire['Title']) ? $questionnaire['Title'] : null;
                    $questionnaires->description = isset($questionnaire['Description']) ? $questionnaire['Description'] : null;
                    $questionnaires->generic_comment = isset($questionnaire['Generic comment']) ? $questionnaire['Generic comment'] : null;
                    $questionnaires->yes_more_info = isset($questionnaire['Yes more info']) ? $questionnaire['Yes more info'] : null;
                    $questionnaires->no_more_info = isset($questionnaire['No more info']) ? $questionnaire['No more info'] : null;
                    $questionnaires->user_id = auth()->id();
                    $questionnaires->save();

                    if (isset($data['Questions'])) {
                        foreach ($data['Questions'] as $question) {
                            if ($question['sl'] == $questionnaire['sl']) {
                                $questions = new QuestionnaireQuestion();
                                $questions->title = isset($question['Title']) ? $question['Title'] : null;
                                $questions->description = isset($question['Description']) ? $question['Description'] : null;
                                $questions->questionnaire_id = $questionnaires->id;
                                $questions->save();

                                if (isset($data['Answers'])) {
                                    foreach ($data['Answers'] as $answer) {
                                        if ($answer['qid'] == $question['sl']) {
                                            $answers = new QuestionnaireAnswer();
                                            $answers->title = $answer['Title'];
                                            $answers->advice = isset($answer['Advice']) ? $answer['Advice'] : null;
                                            $answers->questionnaire_question_id = $questions->id;
                                            $answers->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'msg' => $e->getMessage()]);
        }

        return response()->json(['status' => 200, 'msg' => 'Excel sheet imported successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Questionnaire  $questionnaire
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function result(Request $request, $id)
    {

        // Find the Questionnaire model
        $generic = true;
        $questionnaire = Questionnaire::with('questions.answers')->find($id);
        if (isset($request->answer) && !empty($request->answer)){
            foreach($request->answer as $index => $answer) {
                if (strtolower($request->title[$index][$answer]) == 'yes') {
                    $generic = false;
                    break;
                } else {
                    $generic = true;
                }
            }
        }


        return view('questionnaires.result', compact('questionnaire', 'request', 'generic'));
    }

    public function questionList(){
        // Get all resources
        $questionnaires = Questionnaire::all();

        return view('questionnaires.list', compact('questionnaires'));
    }

    public function showSingle($id)
    {
        // Find the Questionnaire model
        $questionnaire = Questionnaire::with('questions.answers')->find($id);

        return view('questionnaires.show-single', compact('questionnaire'));
    }
}
