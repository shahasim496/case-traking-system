<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccusedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accused_details', function (Blueprint $table) {
            $table->id('AccusedID'); // Primary Key
            $table->string('AccusedName', 50);
            $table->date('AccusedDateOfBirth');
            $table->string('AccusedContact', 50);
            $table->string('AccusedGenderType', 50);
            $table->string('AccusedOtherDetails', 100)->nullable();
            $table->string('AccusedAddress', 100);

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
        Schema::dropIfExists('accused_details');
    }
}
