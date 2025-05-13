<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoEvidenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evidence_id')->constrained('evidences')->onDelete('cascade');
            $table->date('extraction_date');
            $table->string('extracted_from');
            $table->string('extraction_method');
            $table->string('storage_media');
            $table->string('retrieved_by');
            $table->string('contact');
            $table->integer('num_cameras');
            $table->integer('num_videos');
            $table->string('total_length');
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
        Schema::dropIfExists('video_evidences');
    }
}
