<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Teacher;

class TeacherManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('teacher_id', 'like', "%$search%")
                  ->orWhere('college', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }

        if ($request->filled('college')) {
            $query->where('college', $request->college);
        }

        $teachers = $query->join('users', 'teachers.user_id', '=', 'users.id')
            ->select('teachers.*', 'users.name')
            ->orderBy('campus')
            ->orderBy('college')
            ->orderBy('users.name')
            ->paginate(50)->withQueryString();
            
        $campuses = Teacher::select('campus')->whereNotNull('campus')->distinct()->pluck('campus');
        $colleges = Teacher::select('college')->whereNotNull('college')->distinct()->pluck('college');

        return view('admin.teachers.index', compact('teachers', 'campuses', 'colleges'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $teacher->user_id,
            'department_id' => 'nullable|string|max:255',
        ]);

        \DB::transaction(function () use ($request, $teacher) {
            $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $teacher->update([
                'department_id' => $request->department_id,
            ]);
        });

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher profile updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $user = $teacher->user;
        $email = $user ? $user->email : null;

        \DB::transaction(function () use ($teacher, $user, $email) {
            if ($teacher) $teacher->delete();
            if ($user) $user->delete();
            // Clean up associated application record
            if ($email) {
                \App\Models\Application::where('email', $email)->delete();
            }
        });
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Teacher and associated user account deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Teacher and associated accounts removed successfully.');
    }
}
