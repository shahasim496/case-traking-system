<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChainOfCustodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chain_of_custodies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('evidence_id'); // Foreign key to the evidences table
            $table->date('date'); // Date of custody
            $table->time('time'); // Time of custody
            $table->string('delivered_by'); // Person who delivered the evidence
            $table->string('received_by'); // Person who received the evidence
            $table->text('comments')->nullable(); // Additional comments (nullable)
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
        Schema::dropIfExists('chain_of_custodies');
    }
}
