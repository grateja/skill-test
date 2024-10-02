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
            $table->uuid('id');

            $table->string('title');
            $table->text('description');
            $table->string('status')->default('todo')->remarks('todo,in-progress,done');
            $table->string('priority_level')->default('low')->remarks('high,low,medium');

            $table->boolean('archive')->default(0);
            $table->uuid('user_id');
            $table->timestamp('due_date');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('CASCADE');
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
