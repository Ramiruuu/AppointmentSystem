<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('location_latitude')->nullable()->after('appointment_date');
            $table->string('location_longitude')->nullable()->after('location_latitude');
            $table->string('location_address')->nullable()->after('location_longitude');
            $table->string('preferred_location')->nullable()->after('location_address');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['location_latitude', 'location_longitude', 'location_address', 'preferred_location']);
        });
    }
};