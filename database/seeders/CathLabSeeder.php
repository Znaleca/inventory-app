<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\Staff;
use App\Models\StockEntry;
use App\Models\UsageLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CathLabSeeder extends Seeder
{
    public function run(): void
    {
        // ── Categories ──
        $categories = collect([
            ['name' => 'Catheters',      'description' => 'Guiding catheters, diagnostic catheters, balloon catheters'],
            ['name' => 'Stents',         'description' => 'Drug-eluting stents, bare-metal stents, covered stents'],
            ['name' => 'Balloons',       'description' => 'PTCA balloons, cutting balloons, drug-coated balloons'],
            ['name' => 'Guidewires',     'description' => 'Coronary guidewires, peripheral guidewires'],
            ['name' => 'Contrast Media', 'description' => 'Iodinated contrast agents for angiography'],
            ['name' => 'Sheaths',        'description' => 'Introducer sheaths, radial and femoral access'],
            ['name' => 'Manifolds',      'description' => 'Manifold systems and stopcocks'],
        ])->map(fn ($c) => Category::create($c));

        // ── Staff ──
        $staffList = [
            ['name' => 'Maria Santos',    'title' => 'Dr.', 'type' => 'doctor',     'specialization' => 'Interventional Cardiology'],
            ['name' => 'James Reyes',     'title' => 'Dr.', 'type' => 'doctor',     'specialization' => 'Cardiology'],
            ['name' => 'Ana Cruz',        'title' => 'Dr.', 'type' => 'doctor',     'specialization' => 'Peripheral Vascular'],
            ['name' => 'Luis Garcia',     'title' => 'Dr.', 'type' => 'doctor',     'specialization' => 'Electrophysiology'],
            ['name' => 'Rosa Dela Cruz',  'title' => null,  'type' => 'nurse',      'specialization' => 'CathLab Scrub Nurse'],
            ['name' => 'Mark Villanueva', 'title' => null,  'type' => 'nurse',      'specialization' => 'CathLab Circulating Nurse'],
            ['name' => 'Claire Bautista', 'title' => null,  'type' => 'nurse',      'specialization' => 'Recovery Room Nurse'],
            ['name' => 'Jose Ramirez',    'title' => null,  'type' => 'technician', 'specialization' => 'Radiologic Technologist'],
            ['name' => 'Liza Mendoza',    'title' => null,  'type' => 'technician', 'specialization' => 'Cardiac Catheterization Tech'],
            ['name' => 'Carlo Torres',    'title' => null,  'type' => 'technician', 'specialization' => 'Biomedical Equipment Tech'],
        ];

        foreach ($staffList as $s) {
            Staff::create($s);
        }

        // ── Items ──
        $items = [
            ['name' => 'Guiding Catheter 6F JL4',       'sku' => 'CATH-GC-6F-JL4',  'category' => 0, 'unit' => 'pcs',  'unit_price' => 3500,  'reorder_level' => 15],
            ['name' => 'Guiding Catheter 6F JR4',       'sku' => 'CATH-GC-6F-JR4',  'category' => 0, 'unit' => 'pcs',  'unit_price' => 3500,  'reorder_level' => 15],
            ['name' => 'Diagnostic Catheter 5F Pigtail', 'sku' => 'CATH-DX-5F-PIG',  'category' => 0, 'unit' => 'pcs',  'unit_price' => 2800,  'reorder_level' => 10],
            ['name' => 'Xience Alpine DES 3.0x18mm',    'sku' => 'STNT-XA-30-18',   'category' => 1, 'unit' => 'pcs',  'unit_price' => 45000, 'reorder_level' => 5],
            ['name' => 'Resolute Onyx DES 2.5x15mm',    'sku' => 'STNT-RO-25-15',   'category' => 1, 'unit' => 'pcs',  'unit_price' => 42000, 'reorder_level' => 5],
            ['name' => 'NC Emerge PTCA Balloon 3.0x15mm','sku' => 'BLLN-NC-30-15',   'category' => 2, 'unit' => 'pcs',  'unit_price' => 8500,  'reorder_level' => 8],
            ['name' => 'SeQuent Please Neo DCB 2.5x20mm','sku' => 'BLLN-DCB-25-20',  'category' => 2, 'unit' => 'pcs',  'unit_price' => 35000, 'reorder_level' => 3],
            ['name' => 'Runthrough NS Guidewire 0.014"', 'sku' => 'GW-RT-014',       'category' => 3, 'unit' => 'pcs',  'unit_price' => 4200,  'reorder_level' => 20],
            ['name' => 'Sion Blue Guidewire 0.014"',    'sku' => 'GW-SB-014',       'category' => 3, 'unit' => 'pcs',  'unit_price' => 5800,  'reorder_level' => 10],
            ['name' => 'Omnipaque 350 (100ml)',          'sku' => 'CM-OMP-350-100',  'category' => 4, 'unit' => 'vial', 'unit_price' => 2200,  'reorder_level' => 25],
            ['name' => 'Radial Sheath 6F',              'sku' => 'SH-RAD-6F',       'category' => 5, 'unit' => 'pcs',  'unit_price' => 1800,  'reorder_level' => 20],
            ['name' => 'Femoral Sheath 7F',             'sku' => 'SH-FEM-7F',       'category' => 5, 'unit' => 'pcs',  'unit_price' => 2100,  'reorder_level' => 10],
            ['name' => 'Merit Manifold 3-Port',         'sku' => 'MNF-MRT-3P',      'category' => 6, 'unit' => 'set',  'unit_price' => 1500,  'reorder_level' => 15],
        ];

        $createdItems = collect();
        foreach ($items as $data) {
            $createdItems->push(Item::create([
                'name'          => $data['name'],
                'sku'           => $data['sku'],
                'category_id'   => $categories[$data['category']]->id,
                'unit'          => $data['unit'],
                'unit_price'    => $data['unit_price'],
                'reorder_level' => $data['reorder_level'],
            ]));
        }

        // ── Stock Entries ──
        $now = Carbon::now();
        foreach ($createdItems as $item) {
            // First batch (older)
            StockEntry::create([
                'item_id'       => $item->id,
                'quantity'      => rand(20, 50),
                'lot_number'    => 'LOT-' . strtoupper(substr(md5($item->sku . '1'), 0, 8)),
                'expiry_date'   => $now->copy()->addMonths(rand(2, 18)),
                'received_date' => $now->copy()->subDays(rand(30, 90)),
            ]);

            // Second batch (recent)
            StockEntry::create([
                'item_id'       => $item->id,
                'quantity'      => rand(10, 30),
                'lot_number'    => 'LOT-' . strtoupper(substr(md5($item->sku . '2'), 0, 8)),
                'expiry_date'   => $now->copy()->addMonths(rand(6, 24)),
                'received_date' => $now->copy()->subDays(rand(1, 15)),
            ]);
        }

        // Near-expiry stock for dashboard alerts
        StockEntry::create([
            'item_id'       => $createdItems[3]->id, // Stent
            'quantity'      => 3,
            'lot_number'    => 'LOT-EXPIRING',
            'expiry_date'   => $now->copy()->addDays(15),
            'received_date' => $now->copy()->subDays(60),
        ]);

        StockEntry::create([
            'item_id'       => $createdItems[9]->id, // Contrast Media
            'quantity'      => 5,
            'lot_number'    => 'LOT-NEAR-EXP',
            'expiry_date'   => $now->copy()->addDays(7),
            'received_date' => $now->copy()->subDays(45),
        ]);

        // ── Usage Logs ──
        $procedures = ['Coronary Angiography', 'PCI (Angioplasty)', 'Pacemaker Implant', 'EP Study', 'Peripheral Angiography'];
        $doctors     = ['Dr. Maria Santos', 'Dr. James Reyes', 'Dr. Ana Cruz', 'Dr. Luis Garcia'];

        for ($i = 0; $i < 20; $i++) {
            $item = $createdItems->random();
            UsageLog::create([
                'item_id'        => $item->id,
                'quantity_used'  => rand(1, 3),
                'patient_id'     => 'PT-2026-' . str_pad(rand(1, 999), 4, '0', STR_PAD_LEFT),
                'procedure_type' => $procedures[array_rand($procedures)],
                'used_by'        => $doctors[array_rand($doctors)],
                'used_at'        => $now->copy()->subDays(rand(0, 14))->subHours(rand(1, 12)),
            ]);
        }
    }
}
