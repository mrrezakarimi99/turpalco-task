<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Availability;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Product::factory(10)->create()->each(function ($product) {
            Availability::factory(5)->make()->each(function ($availability) use ($product) {
                $availability->product_id = $product->id;
                $availability->save();
            });
        });
    }
}
