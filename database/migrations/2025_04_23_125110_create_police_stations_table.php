<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliceStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('police_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdivision_id')->constrained('subdivisions')->onDelete('cascade'); // Foreign key to subdivisions
            $table->string('name'); // Name of the police station
            $table->text('address')->nullable(); // Address of the police station
            $table->string('contact_number', 20)->nullable(); // Contact number of the police station
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
        Schema::dropIfExists('police_stations');
    }
}
