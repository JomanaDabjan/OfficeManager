<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * =========================================================================
     * RUN THE DATABASE SEEDER
     * =========================================================================
     * This method is triggered when you run 'php artisan db:seed'.
     *
     * THE LOGIC EXPLAINED:
     * We use 'firstOrCreate' to prevent duplication. Instead of deleting
     * everything and creating new rows, this function:
     * 1. Looks for a user with the specific email provided.
     * 2. If found, it does nothing (keeps existing data).
     * 3. If NOT found, it creates a new user with the array of data provided.
     *
     * WHY THIS IS SAFE:
     * This allows you to run 'migrate:fresh' or 'db:seed' as many times as you
     * want without losing your specific admin account or creating duplicates.
     */
    public function run()
    {
        // Define the unique identifier used to find the user
        $searchCriteria = [
            'email' => 'badee@gmail.com'
        ];

        // Define the attributes for the user if they don't exist yet
        $userData = [
            'name'     => 'Badee',
            'password' => Hash::make('17530568'), // Securely hashing the provided password
            'role'     => 'admin'
        ];

        // Execute the operation
        User::firstOrCreate($searchCriteria, $userData);
    }
}