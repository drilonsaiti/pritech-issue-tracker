@extends('layouts.app')

@section('header', 'New Tag')

@section('content')
    <div class="card-surface p-4" style="max-width: 500px;">
        <form action="{{ route('tags.store') }}" method="POST">
            @csrf
            @include('tags._form')
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Cancel</a>
            <button type="submit" class="btn btn-primary-custom">Create Tag</button>
        </form>
    </div>
@endsection
