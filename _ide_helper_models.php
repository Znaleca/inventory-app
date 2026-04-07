<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int $staff_id
 * @property int $quantity_borrowed
 * @property int $quantity_returned
 * @property int $quantity_used
 * @property string $status
 * @property \Illuminate\Support\Carbon $borrowed_at
 * @property \Illuminate\Support\Carbon|null $returned_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\Staff $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereBorrowedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereQuantityBorrowed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereQuantityReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Borrow whereUpdatedAt($value)
 */
	class Borrow extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int $quantity
 * @property string|null $disposed_by
 * @property \Illuminate\Support\Carbon $disposed_at
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereDisposedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereDisposedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Disposal whereUpdatedAt($value)
 */
	class Disposal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $category_id
 * @property int|null $supplier_id
 * @property string $name
 * @property string $condition
 * @property int $stock_used
 * @property string $sku
 * @property string|null $description
 * @property string $unit
 * @property numeric $unit_price
 * @property int $reorder_level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Borrow> $borrows
 * @property-read int|null $borrows_count
 * @property-read \App\Models\Category $category
 * @property-read bool $is_low_stock
 * @property-read string|null $nearest_expiry
 * @property-read int $total_stock
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockEntry> $stockEntries
 * @property-read int|null $stock_entries_count
 * @property-read \App\Models\Supplier|null $supplier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $transfers
 * @property-read int|null $transfers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsageLog> $usageLogs
 * @property-read int|null $usage_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereReorderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereStockUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUpdatedAt($value)
 */
	class Item extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property string $type
 * @property string|null $specialization
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Borrow> $borrowLogs
 * @property-read int|null $borrow_logs_count
 * @property-read string $display_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSpecialization($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUpdatedAt($value)
 */
	class Staff extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int $quantity
 * @property string|null $lot_number
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property \Illuminate\Support\Carbon $received_date
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UsageLog> $usageLogs
 * @property-read int|null $usage_logs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereLotNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereReceivedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockEntry whereUpdatedAt($value)
 */
	class StockEntry extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $contact_person
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Supplier whereUpdatedAt($value)
 */
	class Supplier extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int $quantity
 * @property string $destination
 * @property string|null $transferred_by
 * @property \Illuminate\Support\Carbon $transferred_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTransferredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereTransferredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transfer whereUpdatedAt($value)
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int|null $stock_entry_id
 * @property int $quantity_used
 * @property string $stock_type
 * @property string|null $patient_id
 * @property string|null $procedure_type
 * @property string|null $used_by
 * @property \Illuminate\Support\Carbon $used_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\StockEntry|null $stockEntry
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereProcedureType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereStockEntryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereStockType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereUsedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UsageLog whereUsedBy($value)
 */
	class UsageLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $bio_id
 * @property string $password
 * @property string $role
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

