<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->integer('age')->nullable();
            $table->text('previous_diseases')->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
            $table->softDeletes(); // ← أرشفة بدل الحذف النهائي
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
