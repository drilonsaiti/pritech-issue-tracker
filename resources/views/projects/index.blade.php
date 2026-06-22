@extends('layouts.app')

@section('header', 'Projects')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('projects.create') }}" class="btn btn-primary-custom">New project</a>
    </div>

    <div class="card-surface p-0">
        <table class="table mb-0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Issues</th>
                <th>Start Date</th>
                <th>Deadline</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>

            <tbody>
            @forelse($projects as $project)
                <tr>
                    <td>
                        <a href="{{route('projects.show',$project)}}" class="text-decoration-none cursor-pointer">
                            {{$project->name}}
                        </a>
                    </td>
                    <td>{{$project->issues_count}}</td>
                    <td>{{$project->start_date?->format('M d, Y') ?? '--'}}</td>
                    <td>{{$project->deadline?->format('M d, Y') ?? '--'}}</td>
                    <td class="text-end">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-secondary-custom">Edit</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this project and all its issues?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-secondary-custom text-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty

                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No projects yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <small class="text-muted">
            Showing {{ $projects->firstItem() }}–{{ $projects->lastItem() }}
            of {{ $projects->total() }} projects
        </small>

        {{ $projects->links() }}
    </div>
@endsection
