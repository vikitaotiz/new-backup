<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Medications\CreateMedications;
use App\Http\Requests\Medications\UpdateMedications;
use App\Medication;
use Excel;
use DB;
use Carbon\Carbon;

class MedicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('medications.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medications.create');
    }

    public function import(Request $request)
    {
        if ($request->post()) {
            $rows = json_decode($request['data'])->data;
            $medicationFields = [
                'Medication List',
                'Name'
            ];

            foreach ($rows as $row) {
                $medication = [];
                foreach ($row as $key => $value) {
                    if (in_array($key, $medicationFields)) {
                        $medication['name'] = $value;
                    }
                }
                $medication['user_id'] = auth()->user()->id;

                $medication = Medication::create($medication);
            }
        }
        return response()->json(['success'=>true], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMedications $request)
    {
        $medication = new Medication;

        $medication->name = $request->name;
        $medication->description = $request->description;
        $medication->user_id = auth()->user()->id;

        $medication->save();

        return redirect()->route('medications.index')->with('success', 'Medication created successfully');
    }

    public function medication_ajax(Request $request)
    {
        return $request->all();

        // $medication = new Medication;
        // $medication->name = $request->name;
        // $medication->description = $request->description;
        // $medication->user_id = auth()->user()->id;
        // $medication->save();

    }

    public function prescription_medication(CreateMedications $request)
    {
        $medication = new Medication;

        $medication->name = $request->name;
        $medication->description = $request->description;
        $medication->user_id = auth()->user()->id;

        $medication->save();

        return redirect()->back()->with('success', 'Medication created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medication = Medication::findOrfail($id);

        return view('medications.show', compact('medication'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $medication = Medication::findOrfail($id);

        return view('medications.edit', compact('medication'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMedications $request, $id)
    {
        $medication = Medication::findOrfail($id);

        $medication->name = $request->name;
        $medication->description = $request->description;
        $medication->user_id = auth()->user()->id;

        $medication->save();

        return redirect()->route('medications.show', $medication->id)->with('success', 'Medication updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $medication = Medication::findOrfail($id);

        $medication->delete();

        return redirect()->route('medications.index')->with('success', 'Medication deleted successfully');
    }
    public function uploadFile(Request $request) {
        // Validate form data
        $request->validate([
            'select_file.*' => 'required|mimes:csv,xlsx,xls',
        ]);

        // Checks if the file exists
        if ($request->hasFile('select_file')){
            // Get file name with extension
            $fileNameWithExt = $request->file('select_file')->getClientOriginalName();
            // Get only file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            // Get only extension
            $extension = $request->file('select_file')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $fileName . rand(0000000, 9999999) . "." . $extension;
            // Directory to upload
            $request->file('select_file')->storeAs('public/excel/', $fileNameToStore);
        }

        return response()->json([asset('/storage/excel/'.$fileNameToStore), $fileNameToStore]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allMedications()
    {
        try {
            if(auth()->user() != null && isset(auth()->user()->company->company)){
                auth()->user()->company = auth()->user()->company->company;
            }

            $input = \request()->all();
            $input['keyword'] = isset($input['keyword']) ? $input['keyword'] : '';
            $pageNo = isset($input['pageNo']) && $input['pageNo'] > 0 ? $input['pageNo'] : 1;
            $limit = isset($input['perPage']) ? $input['perPage'] : 10;
            $skip = $limit * ($pageNo - 1);
            $sort_by = isset($input['sort_by']) ? $input['sort_by'] : 'id';
            $order_by = isset($input['order_by']) ? $input['order_by'] : 'desc';

            $total = Medication::select('medications.id')
                ->leftjoin('users AS B','B.id','=','medications.user_id')
                ->where(function ($query) use ($input) {
                    $query->where(function ($q) use ($input) {
                        $q->where('medications.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('medications.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = Medication::select('medications.*',
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('users AS B','B.id','=','medications.user_id')
                ->where(function ($query) use ($input) {
                    $query->where(function ($q) use ($input) {
                        $q->where('medications.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('medications.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $medications = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['medications' => $medications, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
