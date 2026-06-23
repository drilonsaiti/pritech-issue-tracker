@extends('layouts.app')

@section('header', 'Edit Project')

@section('content')
    <div class="card-surface p-4" style="max-width: 600px;">
        <form action="{{ route('projects.update', $project) }}" method="POST">
            @csrf
            @method('PUT')
            @include('projects._form')
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">Update Project</button>
        </form>
    </div>
@endsection
