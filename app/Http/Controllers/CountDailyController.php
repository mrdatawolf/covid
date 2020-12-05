<?php namespace App\Http\Controllers;

use App\Models\CountDaily;
use App\Models\Location;
use Illuminate\Http\Request;

class CountDailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $location           = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $countDaily = CountDaily::where('location_id', $location->id)->latest()->paginate(5);

        return view('countdaily.index', compact('countDaily'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('countdaily.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location           = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $request->validate([
            'count' => 'required',
        ]);
        $request->location_id = $location->id;

        CountDaily::create($request->all());

        return redirect()->route('countdaily.index')
                         ->with('success', 'Count created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $location           = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $countDaily = CountDaily::where('location_id', $location->id)->find($id);

        return view('countdaily.show', compact('countDaily'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id)
    {
        $location           = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $countDaily = CountDaily::where('location_id', $location->id)->find($id);

        return view('countdaily.edit', compact('countDaily'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param                            $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'count' => 'required',
            'created_at' => 'required'
        ]);
        $location           = (\Auth::check()) ? \Auth::user()->location()->first() : Location::find(1);
        $countDaily = CountDaily::where('location_id', $location->id)->find($id);
        $countDaily->count = $request->count;
        $countDaily->created_at = $request->created_at;
        $countDaily->update($request->all());

        return redirect()->route('countdaily.index')
                         ->with('success', 'Count updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\CountDaily $countDaily
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(CountDaily $countDaily): \Illuminate\Http\RedirectResponse
    {
        $countDaily->delete();

        return redirect()->route('countdaily.index')
                         ->with('success', 'Count deleted successfully');
    }
}
