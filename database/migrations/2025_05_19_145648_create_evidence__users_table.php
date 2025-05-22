<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvidenceUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidence__users', function (Blueprint $table) {
            $table->unsignedBigInteger('evidence_id'); // Foreign key for the case
            $table->unsignedBigInteger('user_id'); // Foreign key for the user

            // Add foreign key constraints
            $table->foreign('evidence_id')->references('id')->on('evidences')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();

            // Add unique constraint to prevent duplicate entries
            $table->unique(['evidence_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evidence__users');
    }
}
