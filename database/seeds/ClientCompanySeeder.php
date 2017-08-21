<?php

use Illuminate\Database\Seeder;

class ClientCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $companies = \App\Models\Company::all()->pluck('id')->flatten()->toArray();

        $clients = \App\Models\Client::all();

        foreach ($clients as $client) {
            //Attach companies
            $client->companies()->attach($faker->randomElements($companies, rand(1,3)),
                [
                    'is_confirmed' => true,
                    'confirmed_by_id' => $client->id,
                    'confirmed_by_type' => get_class($client),
                    'confirmed_at' => \Carbon\Carbon::now(),
                    'trainnings_show' => true,
                    'trainnings_edit' => true,
                    'diets_show' => true,
                    'diets_edit' => true,
                    'evaluations_show' => true,
                    'evaluations_edit' => true,
                    'restrictions_show' => true,
                    'restrictions_edit' => true,
                    'exams_show' => true,
                    'exams_edit' => true,
                ]);

            //Avatar
            \App\Models\ClientPhoto::create([
                'client_id' => $client->id,
                'is_profile' => true,
                'is_public' => true,
                'path' => 'assets/isaudavel_holder850.png',
            ]);
        }
    }
}
