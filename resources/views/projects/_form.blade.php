<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $project->name ?? '') }}">
    @error('name')
    <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control"
              rows="3">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description')
    <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control"
               value="{{ old('start_date', isset($project) ? $project->start_date?->format('Y-m-d') : '') }}">
        @error('start_date')
        <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
    <div class="col-6 mb-3">
        <label class="form-label">Deadline</label>
        <input type="date" name="deadline" class="form-control"
               value="{{ old('deadline', isset($project) ? $project->deadline?->format('Y-m-d') : '') }}">
        @error('deadline')
        <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>
</div>
