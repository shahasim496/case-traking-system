<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntityIdToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->unsignedBigInteger('entity_id')->nullable()->after('case_title');
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('set null');
            $table->index('entity_id');
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
            $table->dropForeign(['entity_id']);
            $table->dropIndex(['entity_id']);
            $table->dropColumn('entity_id');
        });
    }
}

