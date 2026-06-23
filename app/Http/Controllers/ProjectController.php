<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    //
    use AuthorizesRequests;

    public function index()
    {
        $projects = Project::withCount('issues')->latest()->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        try {
            $project = Project::create([
                ...$request->validated(),
                'owner_id' => auth()->id()
            ]);

            return redirect()
                ->route('projects.show', $project)
                ->with('success', 'Project created successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('projects.index')
                ->withErrors([ $exception->getMessage()]);
        }
    }

    public function show(Project $project)
    {
        $project->load(['issues' => function ($query) {
            $query->latest();
        }],'owner');

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        try {
            $project->update($request->validated());

            return redirect()
                ->route('projects.show', $project)
                ->with('success', 'Project updated successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('projects.index')
                ->withErrors([ $exception->getMessage()]);
        }
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        try {
            $project->delete();

            return redirect()
                ->route('projects.index')
                ->with('success', 'Project deleted successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('projects.index')
                ->withErrors([ $exception->getMessage()]);
        }
    }
}
