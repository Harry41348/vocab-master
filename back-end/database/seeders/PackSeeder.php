<?php

namespace Database\Seeders;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load the translations from the file
        $translations = include database_path('data/translations.php');

        // Create a pack with translations
        if (! Pack::where('name', 'Dutch')->exists()) {
            Pack::factory()->create([
                'name' => 'Dutch',
                'description' => 'A pack for Dutch translations.',
                'language_from' => 'en',
                'language_to' => 'nl',
                'user_id' => User::where('email', 'harry@laravel.com')->first()->id,
            ])->translations()->createMany($translations);
        }
    }
}
