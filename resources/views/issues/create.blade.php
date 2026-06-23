@extends('layouts.app')

@section('header', 'New Issue')

@section('content')
    <div class="card-surface p-4" style="max-width: 700px;">
        <form action="{{ route('issues.store') }}" method="POST">
            @csrf
            @include('issues._form')
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">Create Issue</button>
        </form>
    </div>
@endsection
