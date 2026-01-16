<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller {
    public function show(Request $request) {
        return $request->user();
    }

    public function update(Request $request) {
        $user = $request->user();
        $data = $request->validate([
            'username' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,'.$user->user_id.',user_id',
            'password' => 'sometimes|min:6',
            // Add gender/dob fields to User model migration if you want to save them
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return response()->json(['message' => 'Profile updated', 'user' => $user]);
    }
}