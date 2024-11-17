<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::factory()->create([
             'first_name' => 'Super',
             'last_name' => 'Admin',
             'name' => 'Super Admin',
             'email' => 'admin@divineglobalgrowth.com',
             'model' => SuperAdmin::class,
             'email_verified_at' => now(),
             'password' => Hash::make('password'),
         ]);
    }
}
