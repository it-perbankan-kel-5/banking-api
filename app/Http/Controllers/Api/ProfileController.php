<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Laravel\Prompts\error;

class ProfileController extends Controller
{
    //
    public function profile_details() {
        $user = \request()->user();

        return response()->json([
            'name' => $user->first_name . $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address
        ]);
    }

    public function edit_profile(Request $request) {
        $user = request()->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:44',
            'email' => 'required',
            'phone_number' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            error($validator->errors());
            return response()->json(['error' => $validator->errors()], 400);
        }
    }
}
