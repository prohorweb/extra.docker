<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use Illuminate\Database\Seeder;

class ClubSeeder extends Seeder
{
    public function run(): void
    {
        $clubs = [
            ['slug' => 'piter', 'title' => 'EXTRASPORT ТК «Питер»', 'position' => 1],
            ['slug' => 'matros', 'title' => 'EXTRASPORT Матроса Железняка', 'position' => 2],
            ['slug' => 'de-vision', 'title' => 'De-Vision', 'position' => 3],
        ];

        foreach ($clubs as $club) {
            Taxonomy::updateOrCreate(
                ['type' => 'club', 'slug' => $club['slug']],
                ['title' => $club['title'], 'position' => $club['position'], 'status' => 10]
            );
        }
    }
}
