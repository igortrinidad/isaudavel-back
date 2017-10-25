<?php

use Illuminate\Database\Seeder;

class ClientNotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $clients = \App\Models\Client::all();

        foreach ($clients as $client) {
            foreach ($client->companies as $company) {

                $nofitication = \App\Models\ClientNotification::create([
                    'client_id' => $client->id,
                    'title' => 'Nova empresa',
                    'content' => 'A empresa ' . $company->name . ' adicionou você como cliente. <br>Gerencie as permissões das informações que poderão ser visualizadas e gerenciadas por esta empresa e seus profissionais',
                    'button_label' => 'Ver empresa',
                    'button_action' => '/cliente/dashboard/' . $client->id . '?tab=companies',
                ]);
            }

            $nofitication = \App\Models\ClientNotification::create([
                'client_id' => $client->id,
                'title' => 'Notificações',
                'content' => 'Não se esqueça de verificar suas notificações regularmente',
            ]);

            $nofitication = \App\Models\ClientNotification::create([
                'client_id' => $client->id,
                'content' => 'Você já verificou sua agenda hoje?',
                'button_label' => 'Visualizar agenda',
                'button_action' => '/cliente/dashboard/' . $client->id . '?tab=calendar',
            ]);


            $trainning = \App\Models\Trainning::where('client_id', $client->id)->with('from')->first();

            if($trainning){
                $nofitication = \App\Models\ClientNotification::create([
                    'client_id' => $client->id,
                    'title' => 'Treinamento adicionado',
                    'content' => $trainning->from->full_name .' adicionou um novo treinamento para você.',
                    'button_label' => 'Visualizar treinamentos',
                    'button_action' => '/cliente/dashboard/' . $client->id . '?tab=trainnings',
                ]);
            }


            $schedule = \App\Models\Schedule::whereHas('subscription', function ($query) use($client) {
                $query->where('client_id', $client->id);
            })->first();

            if($schedule){
                $nofitication = \App\Models\ClientNotification::create([
                    'client_id' => $client->id,
                    'title' => 'Novo agendamento',
                    'content' => 'Você tem um novo agendamento para '. $schedule->date .' às '.  $schedule->time. '.',
                    'button_label' => ' Visualizar agendamento',
                    'button_action' => '/cliente/dashboard/calendar/'.urlencode($schedule->date).'/schedule/'.$schedule->id,
                ]);
            }
        }
    }
}
