<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ServiceSeeder::class,
            StaffSeeder::class,
            UserSeeder::class,
            BridalPackageSeeder::class,
            TestimonialSeeder::class,
            TransformationSeeder::class,
            GallerySeeder::class,
        ]);
    }
}
