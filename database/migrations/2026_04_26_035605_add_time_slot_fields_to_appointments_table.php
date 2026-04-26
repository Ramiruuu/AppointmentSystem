<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('time_slot')->nullable()->after('appointment_date');
            $table->string('queue_number')->nullable()->after('time_slot');
            $table->integer('slot_position')->nullable()->after('queue_number');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['time_slot', 'queue_number', 'slot_position']);
        });
    }
};