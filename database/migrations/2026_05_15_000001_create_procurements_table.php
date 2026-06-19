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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique()->comment('Nomor Purchase Order unik');
            $table->string('supplier_name')->comment('Nama supplier/pemasok');
            $table->string('supplier_contact')->nullable()->comment('Kontak supplier (email/telepon)');
            $table->date('order_date')->comment('Tanggal pemesanan');
            $table->date('expected_delivery_date')->nullable()->comment('Estimasi tanggal pengiriman');
            $table->enum('status', [
                'draft',
                'submitted',
                'approved',
                'in_progress',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('draft')->comment('Status Purchase Order');
            $table->decimal('total_amount', 15, 2)->default(0)->comment('Total harga keseluruhan PO');
            $table->string('currency', 3)->default('IDR')->comment('Mata uang');
            $table->text('notes')->nullable()->comment('Catatan tambahan untuk PO');
            $table->string('created_by')->nullable()->comment('Pembuat PO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procurements');
    }
};
