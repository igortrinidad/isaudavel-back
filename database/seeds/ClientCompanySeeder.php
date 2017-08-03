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
        }
    }
}
