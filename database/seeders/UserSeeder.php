<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@himpunan.or.id'],
            [
                'name' => 'Head of Media Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'designer@himpunan.or.id'],
            [
                'name' => 'Lead Designer Specialist',
                'password' => Hash::make('password123'),
                'role' => 'designer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'panitia@himpunan.or.id'],
            [
                'name' => 'Panitia Acara',
                'password' => Hash::make('password123'),
                'role' => 'requester',
            ]
        );
    }
}
