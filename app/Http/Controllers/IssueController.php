<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Enum\IssuePriority;
use App\Models\Enum\IssueStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Queries\IssueSearchQuery;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IssueSearchQuery $searchQuery,Request $request)
    {
        //
        $tags = Tag::all();
        $statuses = IssueStatus::cases();
        $priorities = IssuePriority::cases();

        $issues = $searchQuery->search($request->only(['tag','status','priority']));


        return view('issues.index', compact('issues', 'tags', 'statuses', 'priorities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $projects = Project::all();
        $statuses = IssueStatus::cases();
        $priorities = IssuePriority::cases();

        return view('issues.create',compact('projects', 'statuses', 'priorities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request)
    {
        //
        try {
            $issue = Issue::create($request->validated());

            return redirect()
                ->route('issues.show', $issue)
                ->with('success', 'Issue created successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->back()
                ->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        //
        $issue->load(['tags','project']);

        return view('issues.show', compact('issue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        //
        $projects = Project::all();
        $statuses = IssueStatus::cases();
        $priorities = IssuePriority::cases();
        return view('issues.edit', compact('issue', 'projects', 'statuses', 'priorities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIssueRequest  $request, Issue $issue)
    {
        //
        try {
            $issue->update($request->validated());

            return redirect()
                ->route('issues.show', $issue)
                ->with('success', 'Issue updated successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->back()
                ->withErrors([$exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        //
        try {
            $issue->delete();

            return redirect()
                ->route('issues.index')
                ->with('success', 'Issue deleted successfully.');
        } catch (\Exception $exception) {
            return redirect()
                ->route('issues.index')
                ->withErrors([ $exception->getMessage()]);
        }
    }
}
