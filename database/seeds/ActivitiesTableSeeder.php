<?php

use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all()->take(10)->pluck('id')->flatten()->toArray();
        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        foreach ($clients as $client) {
            \App\Models\Activity::create([
                'client_id' => $client,
                'xp_earned' => rand(500, 1000),
                'is_confirmed' => true,
                'confirmed_by_id' => $faker->randomElement($professionals),
                'confirmed_by_type' => \App\Models\Professional::class,
                'confirmed_at' => \Carbon\Carbon::now()

            ]);
        }
        */
    }
}
