<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('restrict');

            $table->string('name');                    // snapshot név
            $table->decimal('unit_price', 10, 2);
            $table->integer('qty');
            $table->decimal('line_total', 10, 2);      // snapshot, nem NF-probléma

            $table->timestamps();

            // Opcionális: ugyanaz a product csak 1 sor/ORDER-ben
            // $table->unique(['order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
