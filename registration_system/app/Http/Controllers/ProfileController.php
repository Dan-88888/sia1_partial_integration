<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $student = $user->student;
        $teacher = $user->teacher;
        
        return view('profile.edit', compact('user', 'student', 'teacher'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'contact' => 'nullable|string|max:20',
        ];

        if ($user->role === 'student') {
            $rules['course'] = 'nullable|string|max:255';
            $rules['year_level'] = 'nullable|integer|min:1|max:6';
        } elseif ($user->role === 'teacher') {
            $rules['department_id'] = 'nullable|string|max:255';
        }
        
        $request->validate($rules);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact' => $request->contact ?? $user->contact,
        ]);
        
        if ($user->student) {
            $user->student->update([
                'course' => $request->course,
                'year_level' => $request->year_level,
            ]);
        }

        if ($user->teacher) {
            $user->teacher->update([
                'department_id' => $request->department_id,
            ]);
        }
        
        if ($request->filled('current_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|string|min:8|confirmed',
            ]);
            
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
            
            return redirect()->route('profile.edit')->with('success', 'Profile and password updated successfully.');
        }
        
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}