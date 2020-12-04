<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Charts extends Controller
{
    public function daily() {
        return view('charts.all');
    }
}
