<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedOfficerIdToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->unsignedBigInteger('assigned_officer_id')->nullable()->after('entity_id');
            $table->foreign('assigned_officer_id')->references('id')->on('users')->onDelete('set null');
            $table->index('assigned_officer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->dropForeign(['assigned_officer_id']);
            $table->dropIndex(['assigned_officer_id']);
            $table->dropColumn('assigned_officer_id');
        });
    }
}

