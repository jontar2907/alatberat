@extends('layouts.admin')

@section('title', '403 Unauthorized')

@section('content')
<div class="container mt-5 text-center">
    <h1 class="display-1 text-danger">403</h1>
    <h2>Unauthorized Access</h2>
    <p class="lead">You do not have permission to access this page.</p>
    <a href="{{ route('landing') }}" class="btn btn-primary mt-3">Go to Homepage</a>
</div>
@endsection
