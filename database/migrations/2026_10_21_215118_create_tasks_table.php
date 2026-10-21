<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->constrained('users');
            $table->foreignId('created_by')->constrained('users');
            $table->string('title', 150);
            $table->text('description');
            $table->date('due_date');
            $table->string('priority', 10)->default('medium');
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index(['project_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
