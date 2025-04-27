<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('administrative_unit_id');
            $table->unsignedBigInteger('subdivision_id')->nullable();
            $table->unsignedBigInteger('police_station_id')->nullable();

            $table->foreign('administrative_unit_id')->references('id')->on('administrative_units')->onDelete('cascade');
            $table->foreign('subdivision_id')->references('id')->on('subdivisions')->onDelete('set null');
            $table->foreign('police_station_id')->references('id')->on('police_stations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
