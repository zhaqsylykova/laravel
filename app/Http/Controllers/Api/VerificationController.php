<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'confirmation_code' => 'required|string',
        ]);


        $user = User::where('phone', $request->input('phone'))->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->confirmation_code === $request->input('confirmation_code')) {
            $user->markPhoneAsVerified();
            return response()->json(['success' => 'Phone number confirmed'], 200);
        }

        return response()->json([
            'error' => 'Invalid confirmation code'], 401);
    }

    //protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('auth');
    }
}
