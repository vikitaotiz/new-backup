<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\About;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abouts = About::all();

        return view('abouts.index', compact('abouts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('abouts.create');
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

        $about = About::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $image = $request->image->store('about_images', 'public');

            $about->image = $image;

            $about->save();
        }

        toastr()->success('About Us Content has been created successfully!');

        return redirect()->route('abouts.show', compact('about'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $about = About::findOrFail($id);

        return view('abouts.show', compact('about'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $about = About::findOrFail($id);

        return view('abouts.edit', compact('about'));
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

        $about = About::whereId($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->has('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $about = About::findOrFail($id);

            if($about->image){
                Storage::delete('public/'.$about->image);
            }

            $image = $request->image->store('about_images', 'public');

            $about->image = $image;

            $about->save();
        }

        toastr()->success('About Content has been edited successfully!');

        return redirect()->route('abouts.show', compact('about'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $about = About::findOrFail($id);

        if($about->image){
            Storage::delete('public/'.$about->image);
        }
        
        $about->delete();

        toastr()->success('About Us Content has been deleted successfully!');

        return redirect()->route('abouts.index');

    }
}
