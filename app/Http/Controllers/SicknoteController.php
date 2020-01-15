<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sicknote;
use App\User;
use App\CompanyDetail;

class SicknoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('companyCount')->only(['create', 'store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user() != null && isset(auth()->user()->company->company)){
            auth()->user()->company = auth()->user()->company->company;
        }

        if(auth()->user()->role_id == 3) {
            $sicknotes = Sicknote::where('creator_id', auth()->id())->get();
        } elseif (auth()->user()->role_id == 4) {
            if (count(auth()->user()->companies)) {
                foreach (auth()->user()->companies as $company) {
                    $sicknotes = Sicknote::where('company_id', $company->id)->get();
                }
            } else {
                $sicknotes = collect();
            }
        } elseif (auth()->user()->role_id == 6) {
            foreach (auth()->user()->companies as $company) {
                $sicknotes = Sicknote::where('company_id', $company->id)->get();
            }
        } else {
            $sicknotes = User::with('sicknotes')->get()->map->sicknotes->collapse();
        }

        return view('sicknotes.index', compact('sicknotes'));
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

        $companies = CompanyDetail::all();

        return view('sicknotes.create', compact('users', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
            // 'company_id' => 'required',
        ]);

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $note = new Sicknote;


        $note->name = $request->name;
        $note->body = $request->body;
        $note->user_id = $request->user_id;
        $note->company_id = $company->id;
        $note->creator_id = auth()->user()->id;

        $note->save();

        return redirect('sicknotes')->with('success', 'Sicknote created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $note = Sicknote::findOrfail($id);

        return view('sicknotes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $note = Sicknote::findOrfail($id);

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

        $companies = CompanyDetail::all();

        return view('sicknotes.edit', compact('note', 'users', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'body' => 'required',
            'user_id' => 'required',
        ]);

        if (auth()->user()->company) {
            $company = auth()->user()->company;
        } else {
            $company = $this->activeCompany();
        }

        $note = Sicknote::findOrfail($id);

        $note->name = $request->name;
        $note->body = $request->body;
        $note->company_id = $company->id;
        $note->user_id = $request->user_id;
        $note->creator_id = auth()->user()->id;

        $note->save();

        return redirect()->route('sicknotes.show', $note->id)->with('success', 'Sicknote updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Sicknote::findOrfail($id);

        $note->delete();

        return redirect('sicknotes')->with('success', 'Sicknote deleted successfully.');
    }

    public function activeCompany()
    {
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            if ($company->status == 1) {
               return $company->where('status', 1)->first();
            }
        }
    }
}
