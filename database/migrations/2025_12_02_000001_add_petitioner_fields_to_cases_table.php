<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPetitionerFieldsToCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cases', function (Blueprint $table) {
            $table->string('petitioner_name', 255)->nullable()->after('case_description');
            $table->string('petitioner_id_number', 100)->nullable()->after('petitioner_name');
            $table->enum('petitioner_gender', ['Male', 'Female', 'Other'])->nullable()->after('petitioner_id_number');
            $table->string('petitioner_contact_number', 50)->nullable()->after('petitioner_gender');
            $table->date('petitioner_date_of_birth')->nullable()->after('petitioner_contact_number');
            $table->text('petitioner_address')->nullable()->after('petitioner_date_of_birth');
            
            $table->index('petitioner_id_number');
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
            $table->dropIndex(['petitioner_id_number']);
            $table->dropColumn([
                'petitioner_name',
                'petitioner_id_number',
                'petitioner_gender',
                'petitioner_contact_number',
                'petitioner_date_of_birth',
                'petitioner_address'
            ]);
        });
    }
}

