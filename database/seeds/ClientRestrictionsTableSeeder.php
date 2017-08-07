<?php

use Illuminate\Database\Seeder;

class ClientRestrictionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all()->take(10)->pluck('id')->flatten()->toArray();
        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        foreach ($clients as $client) {
            \App\Models\Restriction::create([
                'client_id' => $client,
                'from_id' => $faker->randomElement($professionals),
                'from_type' => \App\Models\Professional::class,
                'type' => 'medication',
                'restriction' => 'Dipirona',
                'observation' => 'Alergia'
            ]);

            \App\Models\Restriction::create([
                'client_id' => $client,
                'from_id' => $faker->randomElement($professionals),
                'from_type' => \App\Models\Professional::class,
                'type' => 'food',
                'restriction' => 'Hamburger de Siri',
                'observation' => 'Alergia'
            ]);

            \App\Models\Restriction::create([
                'client_id' => $client,
                'from_id' => $faker->randomElement($professionals),
                'from_type' => \App\Models\Professional::class,
                'type' => 'exercise',
                'restriction' => 'Duplo carpado invertido',
                'observation' => 'Deve ser evitado pois quase levou a morte do paciente.'
            ]);
        }
    }
}
