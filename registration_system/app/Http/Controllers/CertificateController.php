<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $enrollments = $student->enrollments()
            ->with('subject')
            ->where('status', 'enrolled')
            ->get();
            
        $totalUnits = $enrollments->sum(function($enrollment) {
            return $enrollment->subject->units;
        });
        
        return view('certificate.index', compact('student', 'enrollments', 'totalUnits'));
    }
}