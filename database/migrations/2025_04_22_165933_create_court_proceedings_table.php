<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourtProceedingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('court_proceedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id'); // Foreign key to the cases table
            $table->string('name'); // Name of the proceeding
            $table->text('description'); // Description of the proceeding
            $table->string('file_path'); // File path for the uploaded document
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('case_id')->references('CaseID')->on('new_case_management')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('court_proceedings');
    }
}
