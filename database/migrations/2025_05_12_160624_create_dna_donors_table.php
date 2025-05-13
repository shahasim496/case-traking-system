<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDnaDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dna_donors', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('evidence_id'); // Foreign key to the evidences table
            $table->string('last_name'); // Donor's last name
            $table->string('first_name'); // Donor's first name
            $table->string('middle_initial')->nullable(); // Donor's middle initial (nullable)
            $table->string('phone')->nullable(); // Donor's phone number (nullable)
            $table->date('dob')->nullable(); // Donor's date of birth (nullable)
            $table->string('gender')->nullable(); // Donor's gender (nullable)
            $table->string('address')->nullable(); // Donor's address (nullable)
            $table->dateTime('collection_datetime')->nullable(); // Date and time of collection (nullable)
            $table->string('id_number')->nullable(); // Donor's identification number (nullable)
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('evidence_id')->references('id')->on('evidences')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dna_donors');
    }
}
