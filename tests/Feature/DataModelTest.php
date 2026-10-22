<?php

use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\UniqueConstraintViolationException;

it('crea usuarios activos por defecto', function () {
    $user = User::factory()->create();

    expect($user->fresh()->is_active)->toBeTrue();
});

it('relaciona el proyecto con quien lo creó', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->for($admin, 'creator')->create();

    expect($project->creator->is($admin))->toBeTrue()
        ->and($admin->createdProjects)->toHaveCount(1);
});

it('permite varios participantes por proyecto y varios proyectos por usuario', function () {
    $users = User::factory(3)->create();
    $projects = Project::factory(2)->create();

    $projects[0]->participants()->attach($users);
    $projects[1]->participants()->attach($users[0]);

    expect($projects[0]->participants)->toHaveCount(3)
        ->and($users[0]->projects)->toHaveCount(2);
});

it('no repite un participante en el mismo proyecto', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create();

    $project->participants()->attach($user);
    $project->participants()->attach($user);
})->throws(UniqueConstraintViolationException::class);

it('agrupa las tareas en su proyecto y su responsable', function () {
    $project = Project::factory()->create();
    $ana = User::factory()->create();
    Task::factory(3)->for($project)->for($ana, 'assignee')->create();

    expect($project->tasks)->toHaveCount(3)
        ->and($ana->assignedTasks)->toHaveCount(3);
});

it('convierte estados y prioridades en enums', function () {
    $project = Project::factory()->create(['status' => 'active']);
    $task = Task::factory()->create(['status' => 'in_progress', 'priority' => 'high']);

    expect($project->fresh()->status)->toBe(ProjectStatus::Active)
        ->and($task->fresh()->status)->toBe(TaskStatus::InProgress)
        ->and($task->fresh()->priority)->toBe(TaskPriority::High);
});

it('siembra el administrador y los datos de ejemplo', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::where('email', 'admin@taskflow.test')->exists())->toBeTrue()
        ->and(Project::count())->toBe(4)
        ->and(Task::count())->toBe(24);
});
