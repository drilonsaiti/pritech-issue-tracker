<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::resource('issues', IssueController::class);
    Route::resource('tags', TagController::class)->except(['show']);
    Route::resource('comments', CommentController::class)->only(['store', 'update','destroy']);
    Route::post('issues/{issue}/tags', [IssueController::class, 'attachTag'])->name('issues.tags.attach');
    Route::delete('issues/{issue}/tags/{tag}', [IssueController::class, 'detachTag'])->name('issues.tags.detach');
    Route::get('issues/{issue}/comments', [IssueController::class, 'getCommentsByIssue'])->name('issues.comments.index');

    Route::post('issues/{issue}/members', [IssueController::class, 'attachMember'])->name('issues.members.attach');
    Route::delete('issues/{issue}/members/{user}', [IssueController::class, 'detachMember'])->name('issues.members.detach');
});

require __DIR__.'/auth.php';
