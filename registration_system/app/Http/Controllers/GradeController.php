<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        
        $enrollments = $student->enrollments()
            ->with(['section.subject', 'grades'])
            ->get();
            
        $grades = $enrollments->map(function($enrollment) {
            $grade = $enrollment->grades->first();
            if ($grade) {
                return [
                    'subject' => $enrollment->section->subject,
                    'section' => $enrollment->section,
                    'midterm' => $grade->midterm_grade,
                    'final'   => $grade->final_grade,
                    'remarks' => $grade->remarks,
                    'units'   => $enrollment->section->subject->units,
                ];
            }
            return null;
        })->filter();
        
        $gwa = $this->calculateGWA($grades);
        
        return view('grades.index', compact('grades', 'gwa'));
    }
    
    private function calculateGWA($grades)
    {
        if ($grades->isEmpty()) return 0;
        
        $totalPoints = 0;
        $totalUnits = 0;
        
        foreach ($grades as $item) {
            if ($item['final']) {
                $totalPoints += $item['final'] * $item['units'];
                $totalUnits += $item['units'];
            }
        }
        
        return $totalUnits > 0 ? round($totalPoints / $totalUnits, 3) : 0;
    }
}