<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToxicologyEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toxicology_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evidence_id')->constrained('evidences')->onDelete('cascade');
            $table->string('item_id');
            $table->string('sample_type');
            $table->integer('quantity');
            $table->string('collection')->nullable();
            $table->text('description')->nullable();
            $table->json('examination')->nullable();
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
        Schema::dropIfExists('toxicology_evidences');
    }
}
