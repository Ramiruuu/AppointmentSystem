<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'phone'    => '1234567890',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $clients = [
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com', 'phone' => '5551001001'],
            ['name' => 'Bob Martinez',  'email' => 'bob@example.com',   'phone' => '5551002002'],
            ['name' => 'Carol White',   'email' => 'carol@example.com', 'phone' => '5551003003'],
        ];

        foreach ($clients as $client) {
            User::create(array_merge($client, [
                'password' => Hash::make('password'),
                'role'     => 'client',
            ]));
        }
    }
}