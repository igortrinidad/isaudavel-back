<?php

use Illuminate\Database\Seeder;

class ProfessionalNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all();

        foreach ($professionals as $professional) {

            foreach ($professional->companies as $company) {

                $nofitication = \App\Models\ProfessionalNotification::create([
                    'professional_id' => $professional->id,
                    'title' => 'Nova empresa',
                    'content' => 'A empresa ' . $company->name . ' adicionou você como professional.',
                    'button_label' => 'Ver empresa',
                    'button_action' => '/profissional/dashboard?tab=companies',
                ]);
            }

            $nofitication = \App\Models\ProfessionalNotification::create([
                'professional_id' => $professional->id,
                'title' => 'Dica iSaudavel',
                'content' => 'Não se esqueça de verificar suas notificações regularmente',
            ]);

            $nofitication = \App\Models\ProfessionalNotification::create([
                'professional_id' => $professional->id,
                'content' => 'Você já verificou sua agenda hoje?',
                'button_label' => 'Visualizar agenda',
                'button_action' => '/profissional/dashboard?tab=calendar',
            ]);

        }
    }
}
