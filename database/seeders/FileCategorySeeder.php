<?php

namespace Database\Seeders;

use App\Models\FileCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FileCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileCategory::create([
            'name' => 'Civil Case',
            'description' => 'Files related to civil case matters'
        ]);

        FileCategory::create([
            'name' => 'Admin Case',
            'description' => 'Files related to administrative cases'
        ]);

        FileCategory::create([
            'name' => 'Government Survey',
            'description' => 'Files related to government surveys'
        ]);

        FileCategory::create([
            'name' => 'Inspection Report',
            'description' => 'Reports and documents related to inspections'
        ]);

        FileCategory::create([
            'name' => 'Request from Survey',
            'description' => 'Requests and documents from surveys'
        ]);
    }
}
