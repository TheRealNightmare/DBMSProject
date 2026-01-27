<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    
    public function show(Request $request) {
        // [UPDATED] Load counts for the current user
        return $request->user()->loadCount(['followers', 'following']);
    }

    public function update(Request $request) {
        $user = $request->user();

        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'bio'      => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}