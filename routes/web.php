<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('projects', ProjectController::class);
Route::resource('tags', TagController::class)->except('show');
Route::resource('issues', IssueController::class);
Route::resource('comments', CommentController::class)->only(['store','update','destroy']);
Route::get('issues/{issue}/comments', [CommentController::class, 'getCommentsByIssue']);
