<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(true)->default(null);
            $table->foreign('parent_id')->references('id')->on('tasks');
            $table->unsignedBigInteger('user_id')->nullable(true)->comment('Task Responsible');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('status', 5)->default('todo')->index()->comment('Status of task');
            $table->tinyInteger('priority')->default(1)->index()->comment('Priority of task');
            $table->string('title', 100)->comment('Title of task');
            $table->text('description')->comment('Description');
            $table->fullText(['title', 'description']);
            $table->datetime('completed_at')->nullable(true)->default(null)->comment('Datetime of setting status done');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
