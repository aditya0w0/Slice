<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user || ! ($user->is_admin ?? false)) {
            abort(403);
        }

        return view('admin.dashboard', [
            'user' => $user,
        ]);
    }
}
