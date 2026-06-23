@extends('layouts.app')

@section('header', $issue->title)

@section('content')
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Back</a>

        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-primary-custom">Edit</a>
        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Delete this issue?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-secondary-custom text-danger">Delete</button>
        </form>
    </div>
    <div class="card-surface p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start">

            <div>
                <p class="mb-2">{{ $issue->description ?? 'No description.' }}</p>
                <span class="badge badge-{{ $issue->status->value }} me-2">{{ $issue->status->label() }}</span>
                <span class="text-muted small">Priority: {{ $issue->priority->label() }}</span>
                <span class="text-muted small ms-3">Due: {{ $issue->due_date?->format('M d, Y') ?? '—' }}</span>
                <div class="text-muted small mt-1">
                    Project: <a href="{{ route('projects.show', $issue->project) }}">{{ $issue->project->name }}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card-surface p-4 mb-4" id="tags-section" data-issue-id="{{ $issue->id }}">
        <h6 class="mb-3">Tags</h6>
        <div id="tags-list" class="d-flex flex-wrap gap-2 mb-3">
            @foreach ($issue->tags as $tag)
                <span class="badge bg-light text-dark border" data-tag-id="{{ $tag->id }}">
                    {{ $tag->name }}
                    <button type="button" class="btn-close btn-close-sm ms-1 detach-tag-btn" style="font-size:.6rem;"></button>
                </span>
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#attachTagModal">
            + Add Tag
        </button>
    </div>

    <div class="card-surface p-4" id="comments-section" data-issue-id="{{ $issue->id }}">
        <h6 class="mb-3">Comments</h6>
        <div id="comments-list" class="mb-3"></div>
        <div id="comments-pagination" class="mb-3"></div>

        <form id="comment-form">
            <div class="mb-2">
                <input type="text" name="author_name" class="form-control" placeholder="Your name">
                <div class="text-danger small mt-1" data-error="author_name"></div>
            </div>
            <div class="mb-2">
                <textarea name="body" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
                <div class="text-danger small mt-1" data-error="body"></div>
            </div>
            <button type="submit" class="btn btn-primary-custom btn-sm">Post Comment</button>
        </form>
    </div>
@endsection

@section('scripts')
@endsection
