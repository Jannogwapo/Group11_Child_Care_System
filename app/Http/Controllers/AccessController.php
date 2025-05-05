<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessController extends Controller
{
    public function access()
    {
        if (!Gate::allows('isAdmin')) {
            abort(403);
        }

        // Fetch users with access_id = 2
        $userAccess = User::where('access_id', 2)->pluck('id')->toArray();

        // Fetch users grouped by roles and filter by $userAccess
        $usersByRole = [
            'admin' => User::where('role_id', 1)->whereIn('id', $userAccess)->get(), // Admin users within $userAccess
            'social_worker' => User::where('role_id', 2)->whereIn('id', $userAccess)->get(), // Social Worker users within $userAccess
        ];

        // Fetch users with access_id = 1 for the "Request" section
        $requests = User::where('access_id', 1)->get();

        return view('admin.access', compact('usersByRole', 'requests'));
    }

    public function toggleUser(Request $request, User $user)
    {
        try {
            // Check if the request is for enabling or disabling
            if ($request->has('enable')) {
                $user->access_id = 2; // Change access_id to 2 (Admin)
            } elseif ($request->has('disable')) {
                $user->access_id = 3; // Change access_id to 3 (Disabled)
            }

            $user->save();

            return back()->with('success', 'User access updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error toggling user access: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating user access.');
        }
    }
}