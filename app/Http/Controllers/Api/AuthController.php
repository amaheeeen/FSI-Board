<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Jamaah;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required', // Email (User) or Passport/Phone (Jamaah). For now assuming Passport for Jamaah.
            'password' => 'required',
            'role' => 'nullable|in:guide,jamaah', // Optional hint
        ]);

        // 1. Try Login as User (Mutawwif / Admin)
        if (Auth::guard('web')->attempt(['email' => $request->identifier, 'password' => $request->password])) {
             $user = User::where('email', $request->identifier)->first();
             return response()->json([
                 'token' => $user->createToken('mobile-app', ['role:guide'])->plainTextToken,
                 'user' => $user,
                 'role' => 'guide'
             ]);
        }

        // 2. Try Login as Jamaah
        // Note: Sanctum doesn't natively support multiple AUTH_GUARDS in default 'attempt', so we check manually
        $jamaah = Jamaah::where('passport_number', $request->identifier)
                        ->orWhere('phone', $request->identifier) 
                        ->first();

        if ($jamaah && \Hash::check($request->password, $jamaah->password)) {
             return response()->json([
                 'token' => $jamaah->createToken('mobile-app', ['role:jamaah'])->plainTextToken,
                 'user' => $jamaah,
                 'role' => 'jamaah'
             ]);
        }

        throw ValidationException::withMessages([
            'identifier' => ['Invalid credentials.'],
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
