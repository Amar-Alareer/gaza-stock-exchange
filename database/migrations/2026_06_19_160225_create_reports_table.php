<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //الشكاوي
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->enum('report_type', ['wrong_price', 'bad_treatment', 'other']);//'سعر_خاطئ'، 'معاملة_سيئة'، 'أخرى'
            $table->text('details');
            $table->enum('status', ['pending', 'resolved', 'rejected'])->default('pending');//'معلق'، 'تم حله'، 'مرفوض'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
