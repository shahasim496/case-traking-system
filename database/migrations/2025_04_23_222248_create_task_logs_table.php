<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskLogsTable extends Migration
{
    public function up()
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to the cases table
            $table->unsignedBigInteger('officer_id'); // Foreign key to the officers table
            $table->string('officer_name');
            $table->string('officer_rank');
            $table->string('department');
            $table->date('date');
            $table->text('description');// Who the task was forwarded to
            $table->text('action_taken'); // Description of the action taken
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('case_id')->references('CaseID')->on('new_case_management')->onDelete('cascade');
            $table->foreign('officer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_logs');
    }
}
