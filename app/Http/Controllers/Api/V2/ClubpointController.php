<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\Rank;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\ClubPoint;
use App\Models\ConvertPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V2\ClubpointCollection;

class ClubpointController extends Controller
{

    public function get_list()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $user_points = ClubPoint::where('user_id', $user->id)->get();
            $total_points = ClubPoint::where('user_id', $user->id)->sum('Points');
            $Total_Expenditure = Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('grand_total');

            $rank = Rank::where('min', '<=', $Total_Expenditure)
                ->where('max', '>=', $Total_Expenditure)
                ->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'user_points' => $user_points,
                    'user_Rank' => $rank,
                    'total_points' => $total_points
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function convert_system_point_into_wallet(Request $request)
    {
        $id = $request->id;
        if (auth()->check()) {
            $user = Auth::user();
            $user_point = ClubPoint::where('id', $id)->first();
            if (!$user_point) {
                return response()->json([
                    'success' => false,
                    'message' => 'Club point not found.'
                ], 404);
            }
            $convert_point = ConvertPoint::first();
            if (!$convert_point) {
                return response()->json([
                    'success' => false,
                    'message' => 'Conversion rate not defined.'
                ], 404);
            }
            $converted_points = $user_point->Points / $convert_point->convert_price;
            $user->balance += $converted_points;
            $user->save();
            $user_point->update(['convert' => 1]);
            return response()->json([
                'success' => true,
                'message' => 'Points converted successfully.',
                'converted_points' => $converted_points,
                'new_balance' => $user->balance
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
    }
}
