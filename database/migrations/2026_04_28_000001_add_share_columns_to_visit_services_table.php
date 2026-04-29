<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إضافة أعمدة حصص الطبيب والعيادة التي يحتاجها ReportService
     */
    public function up(): void
    {
        Schema::table('visit_services', function (Blueprint $table) {
            // نضيف الأعمدة فقط إذا ما كانت موجودة (أمان ضد التشغيل المزدوج)
            if (! Schema::hasColumn('visit_services', 'doctor_percentage')) {
                $table->decimal('doctor_percentage', 5, 2)->default(0)->after('discount');
            }
            if (! Schema::hasColumn('visit_services', 'clinic_percentage')) {
                $table->decimal('clinic_percentage', 5, 2)->default(0)->after('doctor_percentage');
            }
            if (! Schema::hasColumn('visit_services', 'doctor_share')) {
                $table->decimal('doctor_share', 12, 2)->default(0)->after('clinic_percentage');
            }
            if (! Schema::hasColumn('visit_services', 'clinic_share')) {
                $table->decimal('clinic_share', 12, 2)->default(0)->after('doctor_share');
            }
        });
    }

    public function down(): void
    {
        Schema::table('visit_services', function (Blueprint $table) {
            $table->dropColumn(['doctor_percentage', 'clinic_percentage', 'doctor_share', 'clinic_share']);
        });
    }
};
