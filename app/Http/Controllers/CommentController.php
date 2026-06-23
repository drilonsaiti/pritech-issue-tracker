<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //

    public function store(StoreCommentRequest $request)
    {
        try {
            $comment = Comment::create($request->validated());

            return response()->json([
                'message' => 'Comment created successfully.',
                'data' => $comment,
            ], 201);

        } catch (\Exception $exception) {

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        try {
            $comment->update($request->validated());

            return response()->json([
                'message' => 'Comment updated successfully.',
                'data' => $comment,
            ], 201);

        } catch (\Exception $exception) {

            return response()->json([
                'message' => 'Something went wrong.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();
            return response()->json([
                'message' => 'Comment deleted successfully.',
            ],200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong.',
            ],500);
        }
    }
}
