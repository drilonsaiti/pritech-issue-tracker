@extends('layouts.app')

@section('header', 'New Project')

@section('content')
    <div class="card-surface p-4" style="max-width: 600px;">
        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            @include('projects._form')
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">Create Project</button>
        </form>
    </div>
@endsection
