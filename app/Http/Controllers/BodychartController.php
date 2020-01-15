<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bodychart;
use Illuminate\Support\Facades\Storage;

class BodychartController extends Controller
{
    public function index()
    {
        $bodycharts = Bodychart::all();
        return view('bodycharts.index', compact('bodycharts'));
    }

    public function create()
    {
        return view('bodycharts.create');
    }

    public function store(Request $request)
    {
        // Validate form data
        $request->validate([
            'image' => 'required|image|mimes:png',
        ]);
        $image_link = '';
        if($request->hasFile('image')) {
            $image_link = $request->image->store('public/bodycharts');
            $image_link = str_replace("public/", "", $image_link);
        }

        Bodychart::create([
            'link' => $image_link
        ]);
        return redirect()->back()->with('success', 'Bodychart created successfully.');
    }
    
    public function getBodycharts()
    {
        $bodycharts = Bodychart::all();
        return response()->json(['bodycharts' => $bodycharts]);
    }

    public function destroy(Request $request, $id)
    {
        $bodychart = Bodychart::find($id);        
        if ($bodychart) {
            $bodychart->delete();
        }
        return redirect()->back()->with('success', 'Bodychart deleted successfully.');
    }
}
