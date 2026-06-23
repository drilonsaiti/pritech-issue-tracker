@extends('layouts.app')

@section('header', 'Edit Tag')

@section('content')
    <div class="card-surface p-4" style="max-width: 500px;">
        <form action="{{ route('tags.update', $tag) }}" method="POST">
            @csrf
            @method('PUT')
            @include('tags._form')
            <button type="submit" class="btn btn-primary-custom">Update Tag</button>
        </form>
    </div>
@endsection
