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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name'); // Tên sản phẩm tại thời điểm thêm vào giỏ hàng
            $table->string('product_image')->nullable(); // Hình ảnh sản phẩm
            $table->string('product_sku')->nullable(); // Mã SKU của sản phẩm
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Giá sản phẩm tại thời điểm thêm vào giỏ hàng
            $table->decimal('total', 10, 2); // Tổng giá trị (price * quantity)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
