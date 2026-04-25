@extends('layouts.app')

@section('sidebar')
  @if(Auth::user()->role === 'student')
    @include('dashboard.partials.student_sidebar')
  @else
    @include('dashboard.partials.teacher_sidebar')
  @endif
@endsection

@section('page_title', $title)

@section('content')
  <div class="card" style="text-align:center;padding:60px 20px">
    <div style="font-size:3rem;color:var(--navy);margin-bottom:20px"><i class="fas fa-tools"></i></div>
    <h2 style="font-weight:700">{{ $title }}</h2>
    <p style="color:var(--text-light);margin-top:10px">This section is currently under development to provide you with the best experience.</p>
    <a href="{{ route('dashboard') }}" class="btn btn-navy" style="margin-top:20px;display:inline-block">Back to Dashboard</a>
  </div>
@endsection
