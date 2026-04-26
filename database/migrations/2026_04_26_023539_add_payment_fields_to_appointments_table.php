<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('payment_status', ['unpaid', 'partial', 'paid', 'refunded'])->default('unpaid')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_method');
            $table->string('payment_reference')->nullable()->after('amount_paid');
            $table->text('payment_notes')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_notes');
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'payment_method', 'amount_paid', 'payment_reference', 'payment_notes', 'paid_at']);
        });
    }
};