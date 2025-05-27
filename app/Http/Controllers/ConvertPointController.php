<?php

namespace App\Http\Controllers;

use App\Models\ConvertPoint;
use Illuminate\Http\Request;

class ConvertPointController extends Controller
{
    public function index()
    {
        $convert_point = ConvertPoint::first();
        return view('backend.Club_Point.show', compact('convert_point'));
    }
    public function store(Request $request)
    {
        ConvertPoint::create(['convert_price' => $request->number_convert]);
        return redirect()->back();
    }
    public function UpdateConvertPoint(Request $request)
    {
        $update_convert_point = ConvertPoint::first();
        $update_convert_point->update(['convert_price' => $request->number_convert]);
        return redirect()->back();
    }
}
