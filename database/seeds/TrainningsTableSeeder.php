<?php

use Illuminate\Database\Seeder;

class TrainningsTableSeeder extends Seeder
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
            \App\Models\Trainning::create([
                'client_id' => $client,
                'created_by_id' => $faker->randomElement($professionals),
                'created_by_type' => \App\Models\Professional::class,
                'dow' => rand(0, 6),
                /*
                'series' => json_decode('[{"name":"Supino","status":"completed","interval":{"quantity":"10","label":"segundos"},"method":[{"quantity":10,"label":"Repetições","load":"10kg"},{"quantity":10,"label":"Repetições","load":"10kg"}]},{"name":"Esteira","status":"not-completed","interval":{"quantity":"1","label":"minuto"},"method":[{"quantity":10,"label":"Minutos","load":"5km"},{"quantity":5,"label":"Minutos","load":"8km"}]}]'),*/
                'series' => json_decode('[]'),
                'observation' => 'No pain, no gain'
            ]);
        }
    }
}
