<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! User::where('email', 'harry@laravel.com')->exists()) {
            User::factory()->create([
                'name' => 'Harry Wijnschenk',
                'email' => 'harry@laravel.com',
                'password' => 'password',
            ]);
        }
    }
}
