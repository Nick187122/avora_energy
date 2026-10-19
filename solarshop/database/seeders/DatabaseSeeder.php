<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
            'password' => Hash::make('password'),
        ]);

        $this->call([
            SettingsSeeder::class,
            AdminSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            HeroSlideSeeder::class,
            PageSeeder::class,
            PartnerSeeder::class,
            ValuePropositionSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
