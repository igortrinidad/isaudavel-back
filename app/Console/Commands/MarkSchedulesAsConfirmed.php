<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkSchedulesAsConfirmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mark:schedules-as-confirmed-automatically';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark all schedules that passed one hour after the scheduled time.';

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
        $this->info('Command started' );

        $now = Carbon::now()->subHours(3);

        $this->info('Horário do php: '. $now);

        $schedules = Schedule::where('date', $now->format('Y-m-d'))
            ->where('time', $now->format('h:i'))
            ->where('is_canceled', false)
            ->where('is_confirmed', false)
            ->get();

        $this->info('Horários localizados: '. $schedules->count());

        foreach($schedules as $schedule){
            $schedule->is_confirmed = true;
            $schedule->confirmed_at = $now;
            $schedule->confirmed_by = 'iSaudavel Auto Confirmation';
            $schedule->save();
        }

        $this->info('Finished of mark to confirmed all schedules on this time.' );
    }
}
