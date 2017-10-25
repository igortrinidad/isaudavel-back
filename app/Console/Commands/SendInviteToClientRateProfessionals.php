<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Client;
use App\Models\Professional;
use App\Models\Schedule;
use App\Models\SingleSchedule;

class SendInviteToClientRateProfessionals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invite:client:rate:professionals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Invite to clients rates the professionals that they already had schedules with them';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        //Pegar todos os clientes que tiveram aula no ultimo mês e não efetuaram avaliação para os profissionais
        $this->info(date('d/m/Y H:i:s') . ': Command started' );

        $clients = Client::whereHas('subscriptions')
        ->with(['subscriptions' => function($query){
            $query->whereHas('invoices');
            $query->distinct();
            $query->with(['invoices' => function($querytwo){
                $querytwo->whereHas('schedules');
                $querytwo->distinct();
                $querytwo->with(['schedules' => function($querythree){
                    $querythree->select('invoice_id', 'professional_id');
                    $querythree->with(['professional' => function($queryfour){
                        $queryfour->distinct();
                    }]);
                }]);
            }]);
        }])->get();
        
        $this->info('Clientes localizados: ' . $clients->count());

        foreach($clients as $client){
            $this->info('Nome: ' . $client->full_name);

            foreach($client->subscriptions as $subscription){
                
                foreach($subscription->invoices as $invoice){

                    foreach($invoice->schedules as $schedule){

                        $this->info('Cliente: ' . $client->full_name);
                        $this->info('Profissional: ' . $schedule->professional->full_name);

                    }

                }

            }
        }

        //\Mail::queue(new ClientInvoiceReminder($client, $professional));

    }
}
