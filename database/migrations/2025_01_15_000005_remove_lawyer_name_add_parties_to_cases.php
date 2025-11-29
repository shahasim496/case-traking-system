<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLawyerNameAddPartiesToCases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Remove lawyer_name and party_name columns
        Schema::table('cases', function (Blueprint $table) {
            $table->dropColumn('lawyer_name');
            $table->dropColumn('party_name');
        });

        // Create parties table
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('case_id');
            $table->string('party_name', 255);
            $table->text('party_details')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('case_id')->references('id')->on('cases')->onDelete('cascade');
            $table->index('case_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parties');
        
        Schema::table('cases', function (Blueprint $table) {
            $table->string('lawyer_name', 255)->nullable();
            $table->string('party_name', 255);
        });
    }
}

