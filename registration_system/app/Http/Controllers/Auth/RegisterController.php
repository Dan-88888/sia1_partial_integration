<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'student',
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        // Check if this email was pre-admitted by the Admission System
        $existingStudent = Student::whereHas('user', function ($q) use ($request) {
            $q->where('email', $request->email);
        })->first();

        if (!$existingStudent) {
            // No pre-admission record found — create with pending status
            Student::create([
                'user_id'          => $user->id,
                'student_number'   => 'STU-' . date('Y') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'course'           => 'Not Assigned',
                'year_level'       => 1,
                'admission_status' => 'pending',
            ]);
        }

        $this->guard()->login($user);

        return redirect($this->redirectTo());
    }

    protected function guard()
    {
        return auth()->guard();
    }

    protected function redirectTo()
    {
        return '/dashboard';
    }
}