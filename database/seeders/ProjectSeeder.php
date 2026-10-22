<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@taskflow.test')->first();
        $collaborators = User::where('email', '!=', 'admin@taskflow.test')->get();

        Project::factory(4)
            ->for($admin, 'creator')
            ->create()
            ->each(function (Project $project) use ($admin, $collaborators) {
                $participants = $collaborators->random(3);
                $project->participants()->attach($participants);

                foreach ($participants as $participant) {
                    Task::factory(2)
                        ->for($project)
                        ->for($participant, 'assignee')
                        ->for($admin, 'creator')
                        ->create();
                }
            });
    }
}
