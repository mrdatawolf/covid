<?php namespace App\Http\Controllers;

use App\Models\Location;


class Charts extends Controller
{
    public function daily() {

        if(\Auth::check()) {
            $layout =  'layouts.app';
        } else {
            $layout = 'layouts.guest';
        }

        return view('charts.all', compact('layout'));
    }
}
