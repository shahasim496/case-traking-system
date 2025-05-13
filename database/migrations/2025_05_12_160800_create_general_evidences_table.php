<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_evidences', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('evidence_id'); // Foreign key to the evidences table
            $table->string('item_id'); // Item ID
            $table->text('description'); // Description of the evidence
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
        Schema::dropIfExists('general_evidences');
    }
}