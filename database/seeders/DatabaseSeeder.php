<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create tag first
        $tags = Tag::factory(8)->create();

        // Create projects
        $projects = Project::factory(50)->create();

        // For each project,create issues and attach tags & comments
        $projects->each(function (Project $project) use ($tags) {
            Issue::factory(rand(5,10))
                ->create(['project_id' => $project->id])
                ->each(function (Issue $issue) use ($tags) {

                    // Attach tags to issue
                    $issue->tags()->attach(
                        $tags->random(rand(1,3))->pluck('id')->toArray()
                    );

                    // Add comments per issue
                    Comment::factory(rand(0,5))
                        ->create(['issue_id' => $issue->id]);
                });
        });
    }
}
