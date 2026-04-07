<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ── Admin User ──
        User::create([
            'name'     => 'admin',
            'bio_id'   => 'admin',
            'password' => Hash::make('12345'),
            'role'     => 'admin',
        ]);

        $this->call(CathLabSeeder::class);
    }
}
