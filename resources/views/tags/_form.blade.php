<div class="mb-3">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $tag->name ?? '') }}">
    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label class="form-label">Color</label>
    <input type="color" name="color" class="form-control" style="max-width: 100px; height: 42px;"
           value="{{ old('color', $tag->color ?? '#0070f3') }}">
    @error('color') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    <div class="form-text">Optional — pick a color to visually distinguish this tag.</div>
</div>
