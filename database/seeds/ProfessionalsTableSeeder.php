<?php

use Illuminate\Database\Seeder;

class ProfessionalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $categories = \App\Models\Category::where('slug', '<>', 'all')->get()->pluck('id')->flatten()->toArray();

        factory(App\Models\Professional::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\Professional::class)->create([
            'name' => 'Igor',
            'last_name' => 'Trindade',
            'email' => 'igorlucast@hotmail.com',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\Professional::class)->create([
            'name' => 'Andre',
            'last_name' => 'Brandão',
            'email' => 'andrebf4@gmail.com',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\Professional::class, 30)->create();

        $professionals = \App\Models\Professional::all();

        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        foreach($professionals as $professional){

            //Attach categories
            $professional->categories()->attach($faker->randomElements($categories, rand(1,3)));

            //Avatar
            \App\Models\ProfessionalPhoto::create([
                'professional_id' => $professional->id,
                'is_profile' => true,
                'path' => 'assets/isaudavel_holder850.png',
            ]);

            //Professional Rating
            $clients_rating = $faker->randomElements($clients, rand(1,3));
            foreach ($clients_rating as $client) {

                \App\Models\ProfessionalRating::create([
                    'client_id' => $client,
                    'professional_id' => $professional->id,
                    'rating' => rand(1,5),
                    'content' => $faker->sentence(15)
                ]);

            }

        }


    }
}
