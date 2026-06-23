@extends('layouts.app')

@section('header','Issues')

@section('content')

    <form method="GET" action="{{route('issues.index')}}" class="card-surface p-3 mb-3">
        <div class="row g-2 align-items-end">
            <div class="mb-3">
                <input type="text" id="issue-search" class="form-control"
                       placeholder="Search issues by title or description..." value="{{ request('search') }}">
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label small">Status</label>
                <select name="status[]" class="form-select select2" multiple>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}"
                            {{ in_array($status->value, (array) request('status', [])) ? 'selected' : '' }}>
                            {{ $status->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="priority" class="form-label small">Priority</label>
                <select name="priority[]" class="form-select select2" multiple>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->value }}"
                            {{ in_array($priority->value, (array) request('priority', [])) ? 'selected' : '' }}>
                            {{ $priority->label() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="tag" class="form-label small">Tag</label>
                <select name="tag[]" class="form-select select2" multiple>
                    @foreach($tags as $tag)
                        <option value="{{ $tag->id }}"
                            {{ in_array($tag->id, (array) request('tag', [])) ? 'selected' : '' }}>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary-custom">Filter</button>
                <a href="{{ route('issues.index') }}" class="btn btn-secondary-custom">Reset</a>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('issues.create') }}" class="btn btn-primary-custom">New Issue</a>
    </div>

    <div class="card-surface p-0">
        <table class="table mb-0" id="issues-result">
            <thead>
            <tr>
                <th>Title</th>
                <th>Project</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($issues as $issue)
                <tr>
                    <td><a href="{{ route('issues.show', $issue) }}"
                           class="text-decoration-none">{{ $issue->title }}</a></td>
                    <td>{{ $issue->project->name }}</td>
                    <td><span class="badge badge-{{ $issue->status->value }}">{{ $issue->status->label() }}</span></td>
                    <td>{{ $issue->priority->label() }}</td>
                    <td>{{ $issue->due_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="text-end">
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-secondary-custom">Edit</a>

                        <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this issue?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-secondary-custom text-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No issues found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-end align-items-center mt-4">
        {{ $issues->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'All',
                allowClear: true,
                closeOnSelect: false
            });

            const searchInput = $('#issue-search');
            const resultsContainer = $('#issues-result');
            let debounceTimer = null;

            function badgeClass(status) {
                return 'badge-' + status
            }

            function renderResults(paginator) {
                let rows = '';

                if (paginator.data.length === 0) {
                    rows = `<tr><td colspan="5" class="text-center text-muted py-4">No issues found.</td></tr>`;
                } else {
                    paginator.data.forEach((issue) => {
                        rows += `
                            <tr>
                        <td><a href="/issues/${issue.id}" class="text-decoration-none">${issue.title}</a></td>
                        <td>${issue.project ? issue.project.name : ''}</td>
                        <td><span class="badge ${badgeClass(issue.status)}">${issue.status.label}</span></td>
                        <td>${issue.priority}</td>
                        <td>${issue.due_date ?? '—'}</td>
                            </td>
                        `;
                    })
                }

                resultsContainer.html(`
                <div class="card-surface p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Due Date</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
                `);
            }

            searchInput.on('input',function () {
                clearTimeout(debounceTimer);
                const term = searchInput.val();

                debounceTimer = setTimeout(() => {
                    const params = new URLSearchParams(window.location.search);

                    if(term){
                        params.set('search',term);
                    }else{
                        params.delete('search')
                    }

                    params.delete('page')

                    fetch(`{{route('issues.index')}}?${params.toString()}`,{
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                        .then((res) => res.json())
                        .then((response) => renderResults(response.data))
                        .catch(() => {
                            resultsContainer.innerHTML = '<p class="text-danger small">Search failed.</p>';
                        });
                },400)
            })
        });


    </script>
@endsection
