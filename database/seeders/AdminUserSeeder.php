<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['bio_id' => 'admin'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('P@ssw.rd@123'),
                'role' => 'admin',
            ]
        );
    }
}
