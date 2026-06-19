<?php

namespace Database\Seeders;

use App\Models\Procurement;
use App\Models\ProcurementItem;
use Illuminate\Database\Seeder;

class ProcurementSeeder extends Seeder
{
    /**
     * Seed data dummy Purchase Order untuk keperluan testing.
     */
    public function run(): void
    {
        // PO 1 - IC Mikrokontroler
        $po1 = Procurement::create([
            'po_number' => 'PO-20260515-001',
            'supplier_name' => 'PT. Chip Elektronik Indonesia',
            'supplier_contact' => 'sales@chipelektronik.co.id',
            'order_date' => '2026-05-15',
            'expected_delivery_date' => '2026-05-25',
            'status' => 'submitted',
            'total_amount' => 15000000.00,
            'currency' => 'IDR',
            'notes' => 'Urgent untuk lini produksi batch #45',
            'created_by' => 'Admin Procurement',
        ]);

        ProcurementItem::create([
            'procurement_id' => $po1->id,
            'component_name' => 'IC Mikrokontroler ATmega328P',
            'part_number' => 'ATMEGA328P-PU',
            'quantity' => 500,
            'unit' => 'pcs',
            'unit_price' => 25000.00,
            'subtotal' => 12500000.00,
        ]);

        ProcurementItem::create([
            'procurement_id' => $po1->id,
            'component_name' => 'Crystal Oscillator 16MHz',
            'part_number' => 'HC49-16MHZ',
            'quantity' => 500,
            'unit' => 'pcs',
            'unit_price' => 5000.00,
            'subtotal' => 2500000.00,
        ]);

        // PO 2 - Komponen Pasif
        $po2 = Procurement::create([
            'po_number' => 'PO-20260514-001',
            'supplier_name' => 'CV. Komponen Jaya',
            'supplier_contact' => 'order@komponenjaya.com',
            'order_date' => '2026-05-14',
            'expected_delivery_date' => '2026-05-20',
            'status' => 'approved',
            'total_amount' => 3750000.00,
            'currency' => 'IDR',
            'notes' => 'Re-stock komponen pasif rutin',
            'created_by' => 'Admin Procurement',
        ]);

        ProcurementItem::create([
            'procurement_id' => $po2->id,
            'component_name' => 'Resistor 10K Ohm 1/4W',
            'part_number' => 'RES-10K-025W',
            'quantity' => 5000,
            'unit' => 'pcs',
            'unit_price' => 150.00,
            'subtotal' => 750000.00,
        ]);

        ProcurementItem::create([
            'procurement_id' => $po2->id,
            'component_name' => 'Capacitor Ceramic 100nF',
            'part_number' => 'CAP-CER-100NF',
            'quantity' => 3000,
            'unit' => 'pcs',
            'unit_price' => 500.00,
            'subtotal' => 1500000.00,
        ]);

        ProcurementItem::create([
            'procurement_id' => $po2->id,
            'component_name' => 'LED SMD 0805 Red',
            'part_number' => 'LED-SMD-0805-R',
            'quantity' => 3000,
            'unit' => 'pcs',
            'unit_price' => 500.00,
            'subtotal' => 1500000.00,
        ]);

        // PO 3 - PCB
        $po3 = Procurement::create([
            'po_number' => 'PO-20260513-001',
            'supplier_name' => 'PT. PCB Manufacturing',
            'supplier_contact' => 'info@pcbmanufacturing.co.id',
            'order_date' => '2026-05-13',
            'expected_delivery_date' => '2026-05-30',
            'status' => 'in_progress',
            'total_amount' => 50000000.00,
            'currency' => 'IDR',
            'notes' => 'Custom PCB untuk produk baru model X-200',
            'created_by' => 'Manager Procurement',
        ]);

        ProcurementItem::create([
            'procurement_id' => $po3->id,
            'component_name' => 'PCB 4-Layer Custom Model X-200',
            'part_number' => 'PCB-X200-4L-REV2',
            'quantity' => 1000,
            'unit' => 'pcs',
            'unit_price' => 50000.00,
            'subtotal' => 50000000.00,
        ]);
    }
}
