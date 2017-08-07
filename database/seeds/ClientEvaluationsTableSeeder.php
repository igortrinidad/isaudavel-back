<?php

use Illuminate\Database\Seeder;

class ClientEvaluationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all()->pluck('id')->flatten()->toArray();
        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        foreach ($clients as $client) {
            \App\Models\Evaluation::create([
                'client_id' => $client,
                'professional_id' => $faker->randomElement($professionals),
                'items' => json_decode('[{"label":"Peso","value":76,"target":74},{"label":"Gordura corporal","value":15,"target":13},{"label":"IMC","value":24.4,"target":23}]'),
                'observation' => 'Este cara está saradão iSaudavel.'
            ]);
        }

        $evaluations = \App\Models\Evaluation::all()->pluck('id')->flatten()->toArray();

        //evaluation photo
        foreach ($evaluations as $evaluation) {
            \App\Models\EvaluationPhoto::create([
                'evaluation_id' => $evaluation,
                'path' => 'evaluation/photo/8db26b4e742e31c5ff0735c2cc66da09.jpg'
            ]);
        }
    }
}
