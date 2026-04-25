<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('city')->default('Cagayan de Oro');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->json('services_offered')->nullable(); // Store service IDs this hospital offers
            $table->string('operating_hours')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add hospital_id to appointments table
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('hospital_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
        });
        Schema::dropIfExists('hospitals');
    }
};