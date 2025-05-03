<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Providers\AuthServiceProvider;
class AccessController extends Controller
{
    //
    
    public function access()
    {
        // Fetch all users with their roles
        $users = User::with('role')->get();

        // Group users by role name
        $usersByRole = $users->groupBy(function($user) {
            return $user->role->name ?? 'No Role';
        });

        return view('admin.acess', compact('usersByRole'));
    }

    public function delete(User $user)
    {
        if (!Gate::allows('isAdmin')) {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
        }

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.access')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.access')->with('success', 'User deleted successfully.');
    }
}
