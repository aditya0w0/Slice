<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::withCount(['orders', 'loginLogs'])
            ->with('latestKyc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders', 'kycs', 'loginLogs' => function($query) {
            $query->orderBy('logged_in_at', 'desc')->limit(10);
        }]);

        return view('admin.users.show', compact('user'));
    }

    public function toggleAdmin(User $user)
    {
        $user->is_admin = !$user->is_admin;
        $user->save();

        return back()->with('success', $user->is_admin ?
            'User promoted to admin!' :
            'User demoted from admin!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === \Illuminate\Support\Facades\Auth::id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully!');
    }
}
