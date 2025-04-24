<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidencesTable extends Migration
{
    public function up()
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to the cases table
            $table->string('type'); // Type of evidence
            $table->date('date'); // Date of evidence collection
            $table->string('collected_by'); // Collected by
            $table->string('file_path'); // File path for the uploaded evidence
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('case_id')->references('CaseID')->on('new_case_management')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidences');
    }
}
