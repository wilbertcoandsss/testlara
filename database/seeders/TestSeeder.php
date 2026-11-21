<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Test::factory(10)->create();
        Test::insert([
            'name' => 'Testing 1'
        ]);
        Test::insert([
            'name' => 'Testing 2'
        ]);
        Test::insert([
            'name' => 'Testing 3'
        ]);
    }
}
