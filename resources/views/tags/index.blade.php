@extends('layouts.app')

@section('header', 'Tags')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('tags.create') }}" class="btn btn-primary-custom">New Tag</a>
    </div>

    <div class="card-surface p-0">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Color</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($tags as $tag)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $tag->name }}</span>
                    </td>
                    <td>
                        @if ($tag->color)
                            <span class="d-inline-block rounded-circle me-1" style="width:14px;height:14px;background:{{ $tag->color }};border:1px solid #ebebeb;"></span>
                            {{ $tag->color }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('tags.edit', $tag) }}" class="btn btn-sm btn-secondary-custom">Edit</a>
                        <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this tag? It will be removed from all issues.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-secondary-custom text-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-4">No tags yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tags->links() }}
    </div>
@endsection
