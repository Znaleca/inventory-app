<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Item;
use App\Models\StockEntry;

$items = Item::where('item_type', 'device')->get();

foreach ($items as $item) {
    // Check for legacy USED stock gap
    $actualUsed = $item->effective_stock_used;
    $trackedUsedBatches = $item->used_devices_breakdown;
    $trackedUsed = 0;
    
    foreach ($trackedUsedBatches as $bu) {
        $trackedUsed += $bu['remaining'];
    }
    
    if ($actualUsed > $trackedUsed) {
        $missingUsed = $actualUsed - $trackedUsed;
        echo "Item {$item->name}: Missing $missingUsed USED tracked devices. Creating...\n";
        for ($i=0; $i<$missingUsed; $i++) {
            // For a USED device to show up in used_devices_breakdown, it MUST have a BorrowEntry with disposition = 'returned_used'
            // OR according to our NEW logic, it just needs '[USED]' in its serial_number!
            $se = StockEntry::create([
                'item_id' => $item->id,
                'quantity' => 1,
                'serial_number' => '[USED] Legacy-'.strtoupper(substr(uniqid(), -5)),
                'received_date' => now(),
            ]);
        }
    }
}
echo "Done repair.\n";
