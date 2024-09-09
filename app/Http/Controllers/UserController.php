<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function settings()
    {
        return view('ticket.settings');
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            Log::info('Profile photo file detected');
            try {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }
                $path = $request->file('profile_photo')->store('profile_photos', 'public');
                $user->profile_photo = $path;
                Log::info('Profile photo uploaded successfully: ' . $path);
            } catch (\Exception $e) {
                Log::error('Error uploading profile photo: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Failed to upload profile photo.');
            }
        }

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('user.settings')->with('success', 'Settings updated successfully.');
    }
}