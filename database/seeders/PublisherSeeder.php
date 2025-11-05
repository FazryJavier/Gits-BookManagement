<?php

namespace Database\Seeders;

use App\Models\Publisher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $publishers = [
            ['name' => 'Shogakukan', 'address' => 'Tokyo', 'email' => 'contact@shogakukan.com', 'phone' => '0212345678'],
            ['name' => 'Shueisha', 'address' => 'Osaka', 'email' => 'contact@shueisha.com', 'phone' => '02123456789'],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create([
                'name' => $publisher['name'],
                'address' => $publisher['address'],
                'email' => $publisher['email'],
                'phone' => $publisher['phone'],
            ]);
        }
    }
}
