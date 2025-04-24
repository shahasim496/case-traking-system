<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainantDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complainant_details', function (Blueprint $table) {
            $table->id('ComplainantID'); // Primary Key
            $table->string('ComplainantName', 50);
            $table->date('ComplainantDateOfBirth');
            $table->string('ComplainantContact', 50);
            $table->string('ComplainantGenderType', 50);
            $table->string('ComplainantOtherDetails', 100)->nullable();
            $table->string('ComplainantAddress', 100);

            $table->unsignedBigInteger('CaseID'); // Add CaseID column
            $table->foreign('CaseID') // Define foreign key
                  ->references('CaseID')
                  ->on('new_case_management')
                  ->onDelete('cascade');

                  

            
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complainant_details');
    }
}
