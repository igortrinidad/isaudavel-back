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
            'name' => 'Nutrição',
            'slug' => 'nutricao'
        ]);

        \App\Models\Category::create([
            'name' => 'Crossfit',
            'slug' => 'crossfit'
        ]);

        \App\Models\Category::create([
            'name' => 'Massagem e estética',
            'slug' => 'massagem-estetica'
        ]);

        \App\Models\Category::create([
            'name' => 'Acupuntura',
            'slug' => 'acupuntura'
        ]);

        \App\Models\Category::create([
            'name' => 'Academia',
            'slug' => 'academia'
        ]);

        \App\Models\Category::create([
            'name' => 'Dermatologia',
            'slug' => 'dermatologia'
        ]);

        \App\Models\Category::create([
            'name' => 'Cardiologia',
            'slug' => 'cardiologia'
        ]);

        \App\Models\Category::create([
            'name' => 'Ortopedia',
            'slug' => 'ortopedia'
        ]);
    }
}
