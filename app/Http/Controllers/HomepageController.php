<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Homepage;

class HomepageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homepages = Homepage::all();

        return view('homepages.index', compact('homepages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('homepages.create');
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
            'title' => 'required',
        ]);

        $homepage = Homepage::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $image = $request->image->store('slider_images', 'public');

            $homepage->image = $image;

            $homepage->save();
        }

        toastr()->success('Slider Content has been created successfully!');

        return redirect()->route('homepages.show', compact('homepage'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homepage = Homepage::findOrFail($id);

        return view('homepages.show', compact('homepage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homepage = Homepage::findOrFail($id);

        return view('homepages.edit', compact('homepage'));
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
            'title' => 'required',
        ]);

        $homepage = Homepage::whereId($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->has('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $homepage = Homepage::findOrFail($id);

            if($homepage->image){
                Storage::delete('public/'.$homepage->image);
            }

            $image = $request->image->store('slider_images', 'public');

            $homepage->image = $image;

            $homepage->save();
        }

        toastr()->success('Slider Content has been edited successfully!');

        return redirect()->route('homepages.show', compact('homepage'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $homepage = Homepage::findOrFail($id);

        if($homepage->image){
            Storage::delete('public/'.$homepage->image);
        }
        
        $homepage->delete();

        toastr()->success('Slider Content has been deleted successfully!');

        return redirect()->route('homepages.index');

    }
}
