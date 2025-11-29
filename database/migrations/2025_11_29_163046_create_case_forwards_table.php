<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseForwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_forwards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->unsignedBigInteger('forwarded_by');
            $table->unsignedBigInteger('forwarded_to');
            $table->text('message')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->foreign('forwarded_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('forwarded_to')->references('id')->on('users')->onDelete('cascade');
            $table->index('case_id');
            $table->index('forwarded_by');
            $table->index('forwarded_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_forwards');
    }
}
