@extends('layouts.app')

@section('header', 'Edit Issue')

@section('content')
    <div class="card-surface p-4" style="max-width: 700px;">
        <form action="{{ route('issues.update', $issue) }}" method="POST">
            @csrf
            @method('PUT')
            @include('issues._form')
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">Update Issue</button>
        </form>
    </div>
@endsection
