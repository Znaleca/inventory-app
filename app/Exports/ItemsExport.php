<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    protected $items;
    protected $selectedPresets;

    public function __construct(Collection $items, array $selectedPresets = [])
    {
        $this->items = $items;
        $this->selectedPresets = $selectedPresets;
    }

    public function title(): string
    {
        return 'Inventory Report';
    }

    public function collection()
    {
        return $this->items;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Type',
            'Status',
            'New Stock',
            'Used Stock',
            'Lent Out',
            'Reorder Level',
            'Storage Location',
            'Storage Section',
        ];
    }

    public function map($item): array
    {
        $status = 'In Stock';
        if ($item->total_stock <= 0) {
            $status = 'Out of Stock';
        } elseif ($item->total_stock <= ($item->reorder_level ?? 10)) {
            $status = 'Reorder';
        }

        return [
            $item->id,
            $item->name,
            $item->category->name ?? 'Uncategorized',
            ucfirst($item->item_type),
            $status,
            $item->total_stock,
            $item->effective_stock_used,
            $item->active_lent_out ?? 0,
            $item->reorder_level ?? 10,
            $item->storage_location ?? '—',
            $item->storage_section ?? '—',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet  = $event->sheet->getDelegate();
                $count  = $this->items->count();
                $lastRow = $count + 1; // +1 for header row

                // Global sheet defaults for readability
                $sheet->getDefaultRowDimension()->setRowHeight(20);
                $sheet->getStyle("A1:K{$lastRow}")->getFont()->setName('Calibri')->setSize(10);

                // ── Header row styling (clean + professional) ─────────────
                $headerRange = "A1:K1";
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size'  => 11,
                        'name'  => 'Calibri',
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0F4C81'], // professional blue
                    ],
                    'alignment' => [
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'wrapText'   => false,
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color'       => ['argb' => 'FF10B981'], // emerald
                        ],
                    ],
                ]);
                
                // Override alignment for numeric headers to be center aligned
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                $sheet->getRowDimension(1)->setRowHeight(24);

                // ── Data rows ─────────────────────────────────────────────
                for ($row = 2; $row <= $lastRow; $row++) {
                    $isEven = ($row % 2 === 0);
                    $rowBg  = $isEven ? 'FFF5F7FA' : 'FFFFFFFF'; // softer zebra

                    // Base row style
                    $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                        'fill' => [
                            'fillType'   => Fill::FILL_SOLID,
                            'startColor' => ['argb' => $rowBg],
                        ],
                        'font' => [
                            'size' => 10,
                            'name' => 'Calibri',
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_HAIR,
                                'color'       => ['argb' => 'FFD8DEE9'],
                            ],
                        ],
                    ]);

                    // ── Status column (E) color coding ─────────────────────
                    $status = $sheet->getCell("E{$row}")->getValue();
                    if ($status === 'Out of Stock') {
                        $sheet->getStyle("E{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFB91C1C']],
                        ]);
                    } elseif ($status === 'Reorder') {
                        $sheet->getStyle("E{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FFB45309']],
                        ]);
                    } elseif ($status === 'In Stock') {
                        $sheet->getStyle("E{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FF15803D']],
                        ]);
                    }

                    // ── Type column (D) color coding ──────────────────────
                    $type = $sheet->getCell("D{$row}")->getValue();
                    if ($type === 'Device') {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FF4338CA']],
                        ]);
                    } elseif ($type === 'Consumable') {
                        $sheet->getStyle("D{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'color' => ['argb' => 'FF0F766E']],
                        ]);
                    }

                    // ── Numeric columns aligned center ──────────────────────
                    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F{$row}:I{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("F{$row}:I{$row}")->getFont()->setBold(true);
                }

                // ── Outer border ───────────────────────────────────────────
                if ($lastRow >= 1) {
                    $sheet->getStyle("A1:K{$lastRow}")->applyFromArray([
                        'borders' => [
                            'outline' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color'       => ['argb' => 'FF94A3B8'],
                            ],
                        ],
                    ]);
                }

                // ── Freeze panes on header ─────────────────────────────────
                $sheet->freezePane('A2');

                // ── Set minimum column widths for key columns ──────────────
                $sheet->getColumnDimension('A')->setWidth(6);   // ID
                $sheet->getColumnDimension('B')->setWidth(28);  // Name
                $sheet->getColumnDimension('C')->setWidth(18);  // Category
                $sheet->getColumnDimension('D')->setWidth(13);  // Type
                $sheet->getColumnDimension('E')->setWidth(14);  // Status
                $sheet->getColumnDimension('F')->setWidth(11);  // New Stock
                $sheet->getColumnDimension('G')->setWidth(11);  // Used Stock
                $sheet->getColumnDimension('H')->setWidth(10);  // Lent Out
                $sheet->getColumnDimension('I')->setWidth(13);  // Reorder Level
                $sheet->getColumnDimension('J')->setWidth(20);  // Storage Location
                $sheet->getColumnDimension('K')->setWidth(16);  // Storage Section
            },
        ];
    }
}
