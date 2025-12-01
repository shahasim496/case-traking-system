<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkBenchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_benches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('court_id');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('cascade');
            $table->index('court_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_benches');
    }
}

