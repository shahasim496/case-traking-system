<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksAndJudgeNameToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('work_bench_id');
            $table->string('judge_name', 255)->nullable()->after('remarks');
            $table->index('judge_name');
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
            $table->dropIndex(['judge_name']);
            $table->dropColumn(['remarks', 'judge_name']);
        });
    }
}
