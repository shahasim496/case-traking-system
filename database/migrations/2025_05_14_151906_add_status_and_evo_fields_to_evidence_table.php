<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evidences', function (Blueprint $table) {
      
            $table->unsignedBigInteger('evo_officer_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('status_updated_at')->nullable();
            $table->unsignedBigInteger('status_updated_by')->nullable();

            $table->foreign('evo_officer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evidence', function (Blueprint $table) {
            $table->dropForeign(['evo_officer_id']);
            $table->dropForeign(['status_updated_by']);
            $table->dropColumn(['status', 'evo_officer_id', 'notes', 'status_updated_at', 'status_updated_by']);
        });
    }
};