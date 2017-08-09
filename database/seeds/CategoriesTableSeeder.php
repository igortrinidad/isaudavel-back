<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       /* \App\Models\Category::create([
            'name' => 'Todos',
            'slug' => 'all'
        ]);*/

        \App\Models\Category::create([
            'name' => 'Pilates',
            'slug' => 'pilates'
        ]);

        \App\Models\Category::create([
            'name' => 'Personal Trainer',
            'slug' => 'personal'
        ]);

        \App\Models\Category::create([
            'name' => 'Fisioterapia',
            'slug' => 'fisioterapia'
        ]);

        \App\Models\Category::create([
            'name' => 'Massagem',
            'slug' => 'massagem'
        ]);

        \App\Models\Category::create([
            'name' => 'Nutrição',
            'slug' => 'nutricao'
        ]);

        \App\Models\Category::create([
            'name' => 'Saúde',
            'slug' => 'saude'
        ]);
    }
}
