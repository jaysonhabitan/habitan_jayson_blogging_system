<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::transaction(function () {
            $faker = Factory::create();

            for ($i=1; $i < 6; $i++) { 
                $title = $faker->text(20);

                $post = Post::make([
                    'user_id' => $i,
                    'published_by_id' => $i,
                    'category_id' => $i,
                    'title' => $title,
                    'body' => $faker->paragraph(30),
                    'slug' => strtolower(join('-',explode(' ', $title))),
                    'is_visible' => true,
                    'published_at' => now()->toDateTimeString()
                ]);
                
                // save quietly so that observer will not trigger
                $post->saveQuietly();
            }
        });
    }
}
