<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller; // Import the base Controller class
use Illuminate\Http\Request;
use App\Models\Couturier;
use Illuminate\Support\Facades\Auth;

class CouturierAvailabilityController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'availability' => 'required|integer|min:0|max:100',
        ]);

        $user = Auth::user();
        $couturier = Couturier::where('utilisateur_id', $user->id)->first();

        if ($couturier) {
            $couturier->availability = $request->availability;
            $couturier->save();
            return response()->json(['message' => 'Availability saved successfully!']);
        } else {
            return response()->json(['message' => 'Couturier not found'], 404);
        }
    }
}


