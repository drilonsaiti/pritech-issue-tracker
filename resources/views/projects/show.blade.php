@extends('layouts.app')

@section('header', $project->name)

@section('content')
    <div class="d-flex flex-column justify-content-between align-items-start mb-4">
        <div class="mb-4">
            <a href="{{ url()->previous() }}" class="btn btn-secondary-custom">Back</a>

            <a href="{{ route('projects.edit', $project) }}" class="btn btn-primary-custom">Edit</a>
        </div>
        <div class="card-surface p-4 flex-grow-1 me-3">
            <p class="mb-2">{{ $project->description ?? 'No description provided.' }}</p>
            <small class="text-muted">
                {{ $project->start_date?->format('M d, Y') ?? '—' }}
                →
                {{ $project->deadline?->format('M d, Y') ?? '—' }}
            </small>
        </div>

    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Issues</h5>
        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="btn btn-primary-custom btn-sm">
            New Issue
        </a>
    </div>

    <div class="card-surface p-0">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($project->issues as $issue)
                <tr>
                    <td>
                        <a href="{{ route('issues.show', $issue) }}"
                           class="text-decoration-none">{{ $issue->title }}</a>
                    </td>
                    <td>
                        <span class="badge badge-{{ $issue->status->value }}">{{ $issue->status->label() }}</span>
                    </td>
                    <td>{{ $issue->priority->label() }}</td>
                    <td>{{ $issue->due_date?->format('M d, Y') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">No issues yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
