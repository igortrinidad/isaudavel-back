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
            $client->companies()->attach($faker->randomElements($companies, (rand(1,3))));

            //Avatar
            \App\Models\ClientPhoto::create([
                'client_id' => $client->id,
                'is_profile' => true,
                'is_public' => true,
                'path' => 'client/photo/0835f70489e8337b130ee5b5f4c01a61.jpg',
            ]);
        }
    }
}
