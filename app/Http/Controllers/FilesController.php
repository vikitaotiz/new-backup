<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\Files\CreateFile;
use App\Http\Requests\Files\UpdateFile;

use App\File;
use App\User;
use Response;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('files.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $patients = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $patients = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $patients = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $patients = User::where('role_id', 5)->get();
        }

        return view('files.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFile $request)
    {
        // dd($request->all());

        $filename = $request->filename->store('public/patient_files');
        $filename = str_replace("public/", "", $filename);

        $file = new File;

        $file->name = $request->name;

        $file->filename = $filename;

        $file->user_id = $request->user_id;

        $file->creator_id = auth()->user()->id;

        $file->save();

        return redirect('files')->with('success', 'File Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = File::findOrfail($id);

        return view('files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $users = User::where(['role_id' => 5, 'user_id' => auth()->id()])->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
                }
            } else {
                $users = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $users = User::where(['role_id' => 5])->whereIn('user_id', $company->users->pluck('id'))->get();
            }
        } else {
            $users = User::where('role_id', 5)->get();
        }

        $file = File::findOrfail($id);

        $filename = Storage::url($file->filename);

        return view('files.edit', compact('file', 'users', 'filename'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFile $request, $id)
    {
        $file = File::find($id);

        if($request->hasFile('filename')) {

            $filename = $request->filename->store('public/patient_files');
            $filename = str_replace("public/", "", $filename);

            Storage::delete($file->filename);

            $file->filename = $filename;

            $file->save();

        }

        $file->user_id = $request->user_id;

        $file->creator_id = auth()->user()->id;

        $file->save();

        return redirect()->route('files.show', $file->id)->with('success', 'File Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::find($id);

        if($file->filename != 'noimage.jpg')
        {
            Storage::delete($file->filename);
        }

        $file->delete();

        return redirect('files')->with('success', 'File Successfully Deleted.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allFiles()
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

            if (auth()->user()->role_id == 6) {
                $input['doctors'] = auth()->user()->company->users->pluck('id');
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    foreach (auth()->user()->companies as $company) {
                        $input['doctors'] = $company->users->pluck('id');
                    }
                } else {
                    $input['doctors'] = [];
                }
            }

            $total = File::select('files.id')
                ->leftjoin('users AS A','A.id','=','files.user_id')
                ->leftjoin('users AS B','B.id','=','files.creator_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('files.creator_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('files.creator_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('files.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('files.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('files.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = File::select('files.*',
                \DB::raw("CONCAT(A.firstname, ' ', A.lastname) as patient_name"),
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('users AS A','A.id','=','files.user_id')
                ->leftjoin('users AS B','B.id','=','files.creator_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('files.creator_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('files.creator_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('files.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('files.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('files.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'patient') {
                $sql->orderBy(\DB::raw("CONCAT(A.firstname, ' ', A.lastname)"), $order_by);
            } elseif ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $files = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['files' => $files, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
