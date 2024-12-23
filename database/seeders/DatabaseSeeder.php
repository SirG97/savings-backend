<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
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
        // Check if the user already exists
        $existingUser = User::where('email', 'admin@divineglobalgrowth.com')->first();

        if (!$existingUser) {
            User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'name' => 'Super Admin',
                'email' => 'admin@divineglobalgrowth.com',
                'phone' => '09099887768',
                'model' => SuperAdmin::class,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]);

            $this->command->info('Super Admin user created successfully!');
        } else {
            $this->command->info('Super Admin user already exists. No new user was created.');
        }
    }
}
