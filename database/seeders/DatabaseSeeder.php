<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@scraprec.local',
        ], [
            'name' => 'ScrapRec Admin',
            'password' => 'admin12345',
        ]);

        $this->call(ScrapboardRecordDocSeeder::class);
    }
}
