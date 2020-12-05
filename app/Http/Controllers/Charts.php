<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Charts extends Controller
{
    public function daily() {
        $layout = (\Auth::check()) ? 'layouts.app' : 'layouts.guest';

        return view('charts.all', compact('layout'));
    }
}
