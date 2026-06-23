<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Enum\IssuePriority;
use App\Models\Enum\IssueStatus;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Queries\IssueSearchQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IssueSearchQuery $searchQuery,Request $request)
    {
        //
        $issues = $searchQuery->search($request->only(['tag','status','priority','search']));

        if ($request->wantsJson()) {
            return response()->json(['data' => $issues]);
        }

        $tags = Tag::all();
        $statuses = IssueStatus::cases();
        $priorities = IssuePriority::cases();

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
        $issue->load(['tags','project','members']);
        $tags = Tag::all();

        return view('issues.show', compact('issue','tags'));
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

    public function attachTag(Request $request, Issue $issue): JsonResponse
    {
        $request->validate([
            'tag_id' =>  ['required','integer','exists:tags,id'],
        ]);

        $issue->tags()->syncWithoutDetaching($request->tag_id);

        $tag = Tag::find($request->tag_id);

        return response()->json([
            'message' => 'Tag attached',
            'tag' => $tag,
        ],200);
    }

    public function detachTag(Issue $issue,Tag $tag): JsonResponse
    {
        $issue->tags()->detach($tag->id);


        return response()->json([
            'message' => 'Tag detached',
        ],200);
    }

    public function getCommentsByIssue(Issue $issue)
    {
        try {
            $comments = $issue->comments()
                ->latest()
                ->paginate(5);

            $comments->getCollection()->transform(function ($comment) {
                $comment->is_owner = $comment->user_id == auth()->id();
                return $comment;
            });

            return response()->json([
                'data' => $comments,
            ],200);
        }catch (\Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong.',
            ],500);
        }
    }

    public function attachMember(Request $request,Issue $issue): JsonResponse
    {
        $request->validate([
            'user_id' => ['required','integer','exists:users,id'],
        ]);

        $issue->members()->syncWithoutDetaching([$request->user_id]);

        $user = User::find($request->user_id);

        return response()->json([
            'message' => 'Member assigned',
            'member' => $user,
        ],200);
    }

    public function detachMember(Issue $issue,User $user): JsonResponse
    {
        $issue->members()->detach($user);
        return response()->json([
            'message' => 'Member detached',
        ],200);
    }
}
