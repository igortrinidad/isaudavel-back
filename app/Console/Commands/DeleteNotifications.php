<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProfessionalNotification;
use App\Models\ClientNotification;
use Carbon\Carbon;

class DeleteNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all notifications that was readed and that passed 1 one after send.';

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

        $this->info('Started: delete old notifications.' );

        $dateForReaded = Carbon::now()->subDays(5)->format('Y-m-d H:i:s');
        $dateForNonReaded = Carbon::now()->subMonths(2)->format('Y-m-d H:i:s');

        ProfessionalNotification::where('readed_at', '<=', $dateForReaded)->delete();
        ProfessionalNotification::where('created_at', '<=', $dateForNonReaded)->delete();

        ClientNotification::where('readed_at', '<=', $dateForReaded)->delete();
        ClientNotification::where('created_at', '<=', $dateForNonReaded)->delete();

        $this->info('Finished: delete old notifications.' );


    }
}
