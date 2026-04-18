<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('currency');
            // سعر الصرف المستخدم وقت الدفع (اختياري — يُحفظ فقط لو الريسبشن حوّلت من عملة أخرى)
            $table->decimal('exchange_rate', 12, 4)->default(1)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('exchange_rate');
            $table->enum('currency', ['SYP', 'TRY', 'USD'])->default('SYP');
        });
    }
};
