<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBallisticsEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ballistics_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evidence_id')->constrained('evidences')->onDelete('cascade');
            $table->string('item_id');
            $table->text('description');
            $table->integer('firearms')->nullable();
            $table->integer('ammo')->nullable();
            $table->integer('casings')->nullable();
            $table->integer('bullets')->nullable();
            $table->string('examination_requested')->nullable();
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
        Schema::dropIfExists('ballistics_evidences');
    }
}
