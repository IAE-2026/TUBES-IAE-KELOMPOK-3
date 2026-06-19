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
        Schema::create('procurement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('procurement_id')->constrained('procurements')->onDelete('cascade');
            $table->string('component_name')->comment('Nama komponen elektronik');
            $table->string('part_number')->comment('Part number komponen');
            $table->integer('quantity')->comment('Jumlah yang dipesan');
            $table->string('unit')->default('pcs')->comment('Satuan (pcs, lot, roll, dll)');
            $table->decimal('unit_price', 15, 2)->comment('Harga per unit');
            $table->decimal('subtotal', 15, 2)->comment('Subtotal = quantity x unit_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurement_items');
    }
};
