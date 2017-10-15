<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Professional;
use Illuminate\Console\Command;

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


        $this->info('Command finished' );
    }
}
