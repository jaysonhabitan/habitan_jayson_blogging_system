<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * The category name.
     *
     * @var array
     */
    protected $categories = [
        'Food Category',
        'Techonology Category',
        'Clothing Category',
        'Travel Category',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            collect($this->categories)->each(function ($category) {
                Category::create([
                    'name' => $category,
                    'slug' => strtolower(join('-',explode(' ', $category))),
                    'is_visible' => true
                ]);
            });
        });
    }
}
