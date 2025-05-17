<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToEvidencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evidences', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('g_designation'); // Add status column with default value 'pending'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evidences', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove the status column
        });
    }
}
