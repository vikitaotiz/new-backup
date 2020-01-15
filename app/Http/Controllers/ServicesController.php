<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Services\CreateServices;
use App\Http\Requests\Services\UpdateServices;

use App\Service;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('services.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateServices $request)
    {
        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ]);

        return redirect()->route('services.index')->with('success', 'Service Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $service = Service::findOrfail($id);

        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $service = Service::findOrfail($id);

        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateServices $request, $id)
    {
        $service = Service::findOrfail($id);

        $service->name = $request->name;
        $service->description = $request->description;
        $service->user_id = auth()->user()->id;

        $service->save();

        return redirect()->route('services.show', $service->id)->with('success', 'Service Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $service = Service::findOrfail($id);

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Service Deleted Successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allServices()
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

            $total = Service::select('services.id')
                ->leftjoin('users AS B','B.id','=','services.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('services.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('services.user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = Service::select('services.*',
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('users AS B','B.id','=','services.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('services.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('services.user_id', auth()->user()->id);
                            });
                        }
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('services.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('services.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $services = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['services' => $services, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
