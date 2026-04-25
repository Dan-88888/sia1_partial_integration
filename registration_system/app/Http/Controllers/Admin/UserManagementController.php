<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $admins = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        $this->logAction('Created Admin Account', $user);

        return redirect()->route('admin.users.index')->with('success', "Admin account for \"{$user->name}\" created successfully.");
    }

    public function edit(User $user)
    {
        if ($user->role !== 'admin') {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $this->logAction('Updated Admin Account', $user);

        return redirect()->route('admin.users.index')->with('success', "Admin account for \"{$user->name}\" updated successfully.");
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            abort(404);
        }

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own admin account.');
        }

        // Prevent deleting the last admin
        if (User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Cannot delete the last remaining admin account.');
        }

        $name = $user->name;
        $user->delete();

        $this->logAction('Deleted Admin Account', null, ['deleted_admin' => $name]);

        return redirect()->route('admin.users.index')->with('success', "Admin account for \"{$name}\" has been removed.");
    }
}
