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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();


            $table->string('name'); // ชื่อสินค้า
            $table->text('description'); // คำอธิบายสินค้า
            $table->decimal('price', 8, 2); // ราคาสินค้า (สูงสุด 999,999.99)
            $table->string('image')->nullable(); // ที่อยู่รูปภาพสินค้า (สามารถเป็นค่าว่างได้)

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
