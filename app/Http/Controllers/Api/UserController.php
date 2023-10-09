<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;
use function Laravel\Prompts\error;

class UserController extends Controller
{
    public function users() {
        return response()->json([
            \request()->user()
        ]);
    }

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
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:12',
            'last_name' => 'required|max:32',
            'phone_number' => 'required|max:12',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            error($validator->errors());
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $user = User::query()->findOrFail($request->user()->id);

            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;

            $user->saveOrFail();

            return response()->json([
                'message' => 'success',
            ]);
        } catch (Throwable $t) {
            return response()->json([
                'error' => $t->getMessage(),
            ], 500);
        }
    }
}
