<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;

trait AuthenticUser
{
    // Example: Custom login validation logic
    public function customLoginValidation(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    }

    // Example: Custom redirect logic based on user roles
    public function customRedirectAfterLogin($user)
    {
        if ($user->is_admin) {
            return '/admin/dashboard'; // Redirect for admins
        }
        return '/user/dashboard'; // Redirect for regular users
    }

    // Add other custom methods as needed
}
