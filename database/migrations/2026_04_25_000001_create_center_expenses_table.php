<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('center_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->decimal('amount', 14, 2);
            $table->date('expense_date');
            $table->enum('category', ['supplies', 'maintenance', 'other'])->default('supplies');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('center_expenses');
    }
};
