<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\FileCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['pending', 'approved', 'rejected', 'deleted'];
        $categories = FileCategory::all();
        $users = User::all();

        foreach (range(1, 500) as $index) {
            File::create([
                'file_name' => 'File ' . $index,
                'location' => 'Location ' . $index,
                'description' => 'Description for file ' . $index,
                'file' => 'example-file-' . $index . '.pdf',
                'civil_case_number' => 'CCN-' . Str::random(5),
                'lot_number' => 'LOT-' . Str::random(3),
                'status' => $statuses[array_rand($statuses)],
                'file_category_id' => $categories->random()->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
