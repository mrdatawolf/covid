<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): \Illuminate\View\View
    {
        $locations = Location::latest()->paginate(5);

        return view('location.index', compact('locations'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        return view('location.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            'title' => 'required',
            'county' => 'required',
            'state' => 'required',
            'country' => 'required'
        ]);

        Location::create($request->all());

        return redirect()->route('location.index')
                         ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function show(Location $location): \Illuminate\View\View
    {
        //$location = Locaton::find($id);

        return view('location.show', compact('location'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\View\View
     */
    public function edit(Location $location): \Illuminate\View\View
    {
        return view('location.edit', compact('location'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Location     $location
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Location $location): \Illuminate\Http\RedirectResponse
    {
        if($request->has('title')) {
            $location->title = $request->title;
        }
        if($request->has('county')) {
            $location->county = $request->county;
        }
        if($request->has('state')) {
            $location->state = $request->state;
        }
        if($request->has('country')) {
            $location->country = $request->country;
        }
        $location->update($request->all());

        return redirect()->route('location.index')
                         ->with('success', 'Location updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Location $location
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Location $location): \Illuminate\Http\RedirectResponse
    {
        $location->delete();

        return redirect()->route('location.index')
                         ->with('success', 'Location deleted successfully');
    }
}
