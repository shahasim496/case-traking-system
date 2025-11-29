<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHearingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->date('hearing_date');
            $table->text('purpose')->nullable();
            $table->string('person_appearing', 255)->nullable();
            $table->text('outcome')->nullable();
            $table->string('court_order', 255)->nullable();
            $table->date('next_hearing_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->index('case_id');
            $table->index('hearing_date');
            $table->index('next_hearing_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hearings');
    }
}

