<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function settings()
    {
        return view('ticket.settings');
    }

    public function updateSettings(Request $request)
    {
        $ticket = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $ticket->id, // Mengubah ke tabel users
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $ticket->name = $request->name;
        $ticket->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            if ($ticket->profile_photo) {
                Storage::disk('public')->delete($ticket->profile_photo);
            }
            $ticket->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->filled('new_password')) {
            $ticket->password = Hash::make($request->new_password);
        }

        $ticket->save();

        return redirect()->route('user.settings')->with('success', 'Settings updated successfully.');
    }
}