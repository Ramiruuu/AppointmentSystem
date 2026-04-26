<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid');
            }
            if (!Schema::hasColumn('appointments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('appointments', 'payment_reference')) {
                $table->string('payment_reference')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'payment_notes')) {
                $table->text('payment_notes')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'time_slot')) {
                $table->string('time_slot')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'queue_number')) {
                $table->string('queue_number')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'slot_position')) {
                $table->integer('slot_position')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_status', 'payment_method', 'amount_paid', 
                'payment_reference', 'payment_notes', 'paid_at',
                'time_slot', 'queue_number', 'slot_position'
            ]);
        });
    }
};