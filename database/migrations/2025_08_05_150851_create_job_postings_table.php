<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('designation_id')->nullable()->constrained('designations')->onDelete('set null');
            $table->string('pay_scale')->nullable();
            $table->enum('job_type', ['full_time', 'part_time', 'contract', 'temporary', 'internship'])->nullable();
            $table->enum('gender', ['any', 'male', 'female'])->default('any');
            $table->string('job_advertisement')->nullable(); // PDF file path
            $table->text('description');
            $table->text('requirements');
            $table->integer('positions')->default(1);
            $table->integer('age_limit');
            $table->date('deadline');
            $table->enum('status', ['active', 'inactive', 'draft'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_postings');
    }
}
