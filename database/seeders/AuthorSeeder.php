<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'Aoyama Gosho', 'age' => 62, 'birth_date' => '1963-06-21'],
            ['name' => 'Masashi Kishimoto', 'age' => 50, 'birth_date' => '1974-11-08'],
        ];

        foreach ($authors as $author) {
            Author::create([
                'name' => $author['name'],
                'age' => $author['age'],
                'birth_date' => $author['birth_date'],
            ]);
        }
    }
}
