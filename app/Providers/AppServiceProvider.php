<?php

namespace App\Providers;

use App\Models\Comment;
use App\Models\Project;
use App\Policies\CommentPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
    }
}
