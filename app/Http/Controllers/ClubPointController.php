<?php

namespace App\Http\Controllers;

use App\Models\Rank;
use App\Models\ClubPoint;
use App\Models\ConvertPoint;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClubPointController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user_point = ClubPoint::where('user_id', $user->id)->get();
            $total_points = ClubPoint::where('user_id', $user->id)->sum('Points');
            return view('frontend.ClubPoint.club_point', compact('user_point'));
        } else {
            return redirect()->route('login');
        }
    }
    public function convert_into_wallet($id)
    {
        if (auth()->check()) {
            $user = Auth::user();
            $user_point = ClubPoint::where('id', $id)->first();
            $convert_point = ConvertPoint::first();
            $converted_points = $user_point->Points / $convert_point->convert_price;
            $user->balance += $converted_points;
            $user->save();
            $user_point->update(['convert' => 1]);
            return redirect()->back()->with('success', 'Points converted successfully');
        } else {
            return redirect()->route('login');
        }
    }
}
