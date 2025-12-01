<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExistingCourtsWithCourtType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update existing courts based on their name
        \DB::table('courts')->where('name', 'Supreme Court')->update(['court_type' => 'Supreme Court']);
        \DB::table('courts')->where('name', 'High Court')->update(['court_type' => 'High Court']);
        \DB::table('courts')->where('name', 'Session Court')->update(['court_type' => 'Session Court']);
        
        // For any other courts that might exist, set a default or leave null
        // You can customize this based on your needs
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally set all court_type to null
        // \DB::table('courts')->update(['court_type' => null]);
    }
}
