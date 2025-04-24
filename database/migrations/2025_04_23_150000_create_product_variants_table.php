<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('name'); // e.g., "Size", "Color"
            $table->string('value'); // e.g., "Large", "Red"
            $table->decimal('price', 10, 2)->nullable(); // Optional price override
            $table->integer('stock')->default(0);
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variants');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_variants');
        });
    }
}
