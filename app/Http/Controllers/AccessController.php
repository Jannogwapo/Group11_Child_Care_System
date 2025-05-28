<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessController extends Controller
{
    public function access()
    {
        if (!Gate::allows('It')) {
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

        // Fetch disabled users
        $disabledUsers = User::where('access_id', 3)->get();

        return view('admin.access', compact('usersByRole', 'requests', 'disabledUsers'));
    }

    public function toggleUser(Request $request, User $user)
    {
        try {
            // Update the user's access_id based on the request
            if ($request->has('access_id')) {
                $user->access_id = $request->access_id;
                $user->save();
            }

            return back()->with('success', 'User access updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error toggling user access: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating user access.');
        }
    }
}