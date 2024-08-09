<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResultsController extends Controller
{
    public function index(){
        $results = session('results');
        if (!$results) {
            return redirect()->route('home');
        }
                
        return view('questions/results', compact('results'));
    }
}
