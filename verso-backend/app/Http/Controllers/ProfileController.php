<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    
    public function show(Request $request) {
        return $request->user();
    }

    public function update(Request $request) {
        $user = $request->user();

        // Validate Inputs
        // Note: We ignore the current user's ID for unique checks
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'email'    => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'bio'      => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ]);

        // Handle Image Upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists (optional, prevents clutter)
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store in 'public/profile_images' folder
            // This returns the relative path like "profile_images/filename.jpg"
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        // Update User
        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}