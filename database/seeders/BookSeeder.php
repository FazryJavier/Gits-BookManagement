<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Naruto',
                'isbn' => '9780451524935',
                'description' => 'Naruto Shonen',
                'pages' => 328,
                'author_id' => 1,
                'publisher_id' => 1,
            ],
            [
                'title' => 'Naruto Shipuden',
                'isbn' => '9780747532699',
                'description' => 'Naruto Shipuden Shonen',
                'pages' => 223,
                'author_id' => 1,
                'publisher_id' => 1,
            ],
            [
                'title' => 'Detective Conan',
                'isbn' => '9781400079278',
                'description' => 'Detective Conan Chapter X',
                'pages' => 900,
                'author_id' => 1,
                'publisher_id' => 2,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
