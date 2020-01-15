<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Offer;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::all();

        return view('offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('offers.create');
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

        $offer = Offer::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->hasFile('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $image = $request->image->store('offer_images', 'public');

            $offer->image = $image;

            $offer->save();
        }

        toastr()->success('Service Content has been created successfully!');

        return redirect()->route('offers.show', compact('offer'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offer = Offer::findOrFail($id);

        return view('offers.show', compact('offer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $offer = Offer::findOrFail($id);

        return view('offers.edit', compact('offer'));
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

        $offer = Offer::whereId($id)->update([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->user()->id
        ]);

        if($request->has('image')) {

            $this->validate($request, [
                'image' => 'file|image|mimes:jpeg,jpg,png,gif|max:10000'
            ]);

            $offer = Offer::findOrFail($id);

            if($offer->image){
                Storage::delete('public/'.$offer->image);
            }

            $image = $request->image->store('offer_images', 'public');

            $offer->image = $image;

            $offer->save();
        }

        toastr()->success('Service Content has been edited successfully!');

        return redirect()->route('offers.show', compact('offer'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);

        if($offer->image){
            Storage::delete('public/'.$offer->image);
        }
        
        $offer->delete();

        toastr()->success('Service Content has been deleted successfully!');

        return redirect()->route('offers.index');

    }
}
