<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCaseFieldsToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->unsignedBigInteger('case_type_id')->nullable()->after('case_number');
            $table->unsignedBigInteger('court_id')->nullable()->after('case_type_id');
            $table->unsignedBigInteger('work_bench_id')->nullable()->after('court_id');
            $table->text('case_description')->nullable()->after('case_title');
            
            $table->foreign('case_type_id')->references('id')->on('case_types')->onDelete('set null');
            $table->foreign('court_id')->references('id')->on('courts')->onDelete('set null');
            $table->foreign('work_bench_id')->references('id')->on('work_benches')->onDelete('set null');
            
            $table->index('case_type_id');
            $table->index('court_id');
            $table->index('work_bench_id');
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
            $table->dropForeign(['case_type_id']);
            $table->dropForeign(['court_id']);
            $table->dropForeign(['work_bench_id']);
            $table->dropIndex(['case_type_id']);
            $table->dropIndex(['court_id']);
            $table->dropIndex(['work_bench_id']);
            $table->dropColumn(['case_type_id', 'court_id', 'work_bench_id', 'case_description']);
        });
    }
}
