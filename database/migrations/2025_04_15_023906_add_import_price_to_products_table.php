<?php

namespace Database\Migrations;

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
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('import_price', total: 10, places: 2)
                      ->nullable()
                      ->after('price')
                      ->comment('Giá nhập hàng của sản phẩm');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'import_price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('import_price');
            });
        }
    }
};