<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

     public function up(): void
{
    Schema::create('weight_logs', function (Blueprint $table) {
        $table->id();
        $table->decimal('weight', 5, 2); // รองรับน้ำหนักเช่น 999.99
        $table->date('recorded_at');     // วันที่บันทึก
        $table->string('note')->nullable(); // บันทึกเพิ่มเติม
        $table->timestamps();
    });
}



   public function down(): void
    {
        Schema::dropIfExists('weight_logs');
    }
};
