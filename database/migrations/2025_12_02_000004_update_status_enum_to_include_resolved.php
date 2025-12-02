<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumToIncludeResolved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // For MySQL, we need to modify the ENUM type
        DB::statement("ALTER TABLE `cases` MODIFY COLUMN `status` ENUM('Open', 'Closed', 'Resolved') DEFAULT 'Open'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert back to original ENUM values
        // First, update any 'Resolved' statuses to 'Closed'
        DB::statement("UPDATE `cases` SET `status` = 'Closed' WHERE `status` = 'Resolved'");
        
        // Then modify the ENUM back
        DB::statement("ALTER TABLE `cases` MODIFY COLUMN `status` ENUM('Open', 'Closed') DEFAULT 'Open'");
    }
}

