<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('user_id')->nullable(); // User who performed the action
            $table->string('category', 50); // case, notice, hearing, forwarding, comment
            $table->string('activity_type', 50); // created, updated, deleted, forwarded, commented
            $table->string('entity_type', 50)->nullable(); // Case, Notice, Hearing, CaseForward, CaseComment
            $table->unsignedBigInteger('entity_id')->nullable(); // ID of the related entity (notice_id, hearing_id, etc.)
            $table->text('description'); // Description of the activity
            $table->json('old_data')->nullable(); // Old data before update (for edit operations)
            $table->json('new_data')->nullable(); // New data after update (for edit operations)
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('case_id');
            $table->index('category');
            $table->index('activity_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_logs');
    }
}
