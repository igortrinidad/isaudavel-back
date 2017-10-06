<?php

use Illuminate\Database\Seeder;

class ProfessionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Professional without company
         */

        $terms = ['accepted' => true, 'accepted_at' => \Carbon\Carbon::now()->format('d/m/Y H:i:s')];

        $professional = factory(App\Models\Professional::class)->create([
            'name' => 'Manolo',
            'last_name' => 'Sauro',
            'email' => 'contato@manolosauro.com.br',
            'slug' => str_random(10),
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'terms' => $terms
        ]);

        $professional->subscription()->create([
            'clients' => 30,
            'total' => 19.90,
            'is_active' => true,
            'start_at' => \Carbon\Carbon::now()->format('d/m/Y'),
            'expire_at' => \Carbon\Carbon::now()->addMonth(1)->format('d/m/Y')
        ]);

        $clients = \App\Models\Client::all()->take(rand(3, 5));

        foreach ($clients as $client) {

            //Attach clients
            $professional->clients()->attach( ['client_id' => $client->id],[
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
        }

    }
}
