<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $organizations = Organization::orderBy('name')->get();
        return view('locate', compact('organizations'));
    }
}