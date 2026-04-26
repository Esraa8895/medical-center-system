<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPaymentsTableRemoveCurrency extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'currency')) {
                $table->dropColumn('currency');
            }

            $table->decimal('exchange_rate', 12, 4)
                  ->default(1)
                  ->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'exchange_rate')) {
                $table->dropColumn('exchange_rate');
            }

            if (!Schema::hasColumn('payments', 'currency')) {
                $table->enum('currency', ['SYP', 'TRY', 'USD'])
                      ->default('SYP');
            }
        });
    }
}
