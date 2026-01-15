<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validated = $request->validate([
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create default shelves for new user
        $user->shelves()->createMany([
            ['name' => 'History', 'is_system_default' => true],
            ['name' => 'Favorites', 'is_system_default' => true],
            ['name' => 'Storage', 'is_system_default' => true],
        ]);

        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken]);
    }

    public function login(Request $request) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken, 'user' => $user]);
    }
}