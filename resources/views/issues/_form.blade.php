<div class="mb-3">
    <label class="form-label">Project</label>
    <select name="project_id" class="form-select">
        <option value="">Select a project</option>
        @foreach ($projects as $project)
            <option value="{{ $project->id }}"
                {{ old('project_id', $issue->project_id ?? request('project_id')) == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
    @error('project_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>



<div class="mb-3">
    <label class="form-label">Title</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $issue->title ?? '') }}">
    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $issue->description ?? '') }}</textarea>
    @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-4 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}"
                    {{ old('status', $issue->status->value ?? 'open') == $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
            @endforeach
        </select>
        @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Priority</label>
        <select name="priority" class="form-select">
            @foreach ($priorities as $priority)
                <option value="{{ $priority->value }}"
                    {{ old('priority', $issue->priority->value ?? 'medium') == $priority->value ? 'selected' : '' }}>
                    {{ $priority->label() }}
                </option>
            @endforeach
        </select>
        @error('priority') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-4 mb-3">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control"
               value="{{ old('due_date', isset($issue) ? $issue->due_date?->format('Y-m-d') : '') }}">
        @error('due_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>
