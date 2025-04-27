<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewCaseManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_case_management', function (Blueprint $table) {
            $table->id('CaseID', 50);
            $table->integer('CaseDepartmentID');
            $table->string('CaseDepartmentName', 50);
            $table->string('CaseType');
            $table->timestamp('CaseCreationDate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('CaseStatus', 50);
            $table->string('CaseDescription', 50);  
            $table->string('OfficerID', 100);
            $table->string('LastOfficerID', 100)->default('0')->nullable();
            $table->string('OfficerName', 100);
            $table->integer('OfficerRank');
            $table->integer('administrative_unit_id');
            $table->integer('subdivision_id');
            $table->integer('police_station_id');
            $table->timestamps(); // Created_at and Updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_case_management');
    }
}
