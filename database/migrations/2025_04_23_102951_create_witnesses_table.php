<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWitnessesTable extends Migration
{
    public function up()
    {
        Schema::create('witnesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to the cases table
            $table->string('name'); // Witness name
            $table->text('address'); // Witness address
            $table->string('national_id'); // Witness national ID
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('case_id')->references('CaseID')->on('new_case_management')->onDelete('cascade');
        });

        Schema::create('witness_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('witness_id'); // Foreign key to the witnesses table
            $table->string('file_name'); // File name
            $table->string('file_path'); // File path
            $table->string('file_type'); // File type (e.g., document, video)
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('witness_id')->references('id')->on('witnesses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('witness_files');
        Schema::dropIfExists('witnesses');
    }
}
