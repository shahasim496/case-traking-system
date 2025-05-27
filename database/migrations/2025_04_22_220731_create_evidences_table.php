<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidencesTable extends Migration
{
    public function up()
    {
        Schema::create('evidences', function (Blueprint $table) {
            $table->id(); // Primary key
            // Foreign key to the cases table
            $table->string('type')->nullable(); // Type of evidence (e.g., dna, ballistics, etc.)
            $table->string('officer_id'); // ID of the officer submitting the evidence
            $table->string('officer_name'); // Name of the officer
            $table->string('designation'); // Officer's designation
            $table->string('g_officer_id'); // ID of the government officer
            $table->string('g_officer_name'); // Name of the government officer
            $table->string('g_designation'); // Government officer's designation
            $table->string('case_id'); 
            $table->string('case_description'); 
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('evidences');
    }
}
