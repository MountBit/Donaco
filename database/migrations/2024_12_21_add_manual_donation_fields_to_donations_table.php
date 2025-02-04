<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'payment_method')) {
                $table->enum('payment_method', ['mercadopago', 'manual'])->default('mercadopago');
            }

            if (!Schema::hasColumn('donations', 'proof_file')) {
                $table->string('proof_file')->nullable();
            }

            if (!Schema::hasColumn('donations', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
        });
    }

    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'proof_file']);

            if (Schema::hasColumn('donations', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
