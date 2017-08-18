<?php

namespace App\Console\Commands;

use App\Models\ClientSubscription;
use App\Models\Invoice;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProccessSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate client invoices and shedules';

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

        $date = Carbon::now()->format('Y-m-d');

        $client_subscriptions = ClientSubscription::where('expire_at', $date)
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->with('client', 'plan')
            ->get();

        $this->info('Processing '. $client_subscriptions->count() . ' subscription(s)' );

        foreach ($client_subscriptions as $client_subscription) {

            $old_start = Carbon::createFromFormat('d/m/Y', $client_subscription->start_at);
            $old_expire = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at);

            $new_start = $old_start->addMonths($client_subscription->plan->expiration);
            $new_expire = $old_expire->addMonths($client_subscription->plan->expiration);

            $new_invoice = Invoice::create([
                'subscription_id' => $client_subscription->id,
                'company_id' => $client_subscription->company_id,
                'value' => $client_subscription->plan->value,
                'expire_at' => $new_expire->format('d/m/Y'),
                'is_confirmed' => false,
                'is_canceled' => false,
                'history' => json_decode('[]')
            ]);


            $new_schedules = [];

            $i = 0;
            for ($new_start; count($new_schedules) < $client_subscription->quantity; $new_start->addDays(1, 'days')) {


                if ($client_subscription->workdays[$i]['dow'] == $new_start->dayOfWeek) {

                    $schedule_data = [
                        'subscription_id' => $client_subscription->id,
                        'company_id' => $client_subscription->company_id,
                        'date' => $new_start->format('d/m/Y'),
                        'time' => $client_subscription->workdays[$i]['init'],
                        'professional_id' => $client_subscription->workdays[$i]['professional_id'],
                        'invoice_id' => $new_invoice->id
                    ];

                    $new_schedule = Schedule::create($schedule_data);

                    $new_schedules[] = $new_schedule;

                    $i++;

                    if ($i == count($client_subscription->workdays)) {
                        $i = 0;
                    }

                    while ($client_subscription->workdays[$i]['dow'] == $new_start->dayOfWeek && count($new_schedules) < $client_subscription->quantity) {

                        dd('teste');

                        $schedule_data['date'] = $new_start->format('d/m/Y');
                        $schedule_data['time'] = $client_subscription->workdays[$i]['init'];

                        $new_schedule = Schedule::create($schedule_data);

                        $new_schedules[] = $new_schedule;

                        $i++;
                    }

                }

            }

        }

        $this->info('Finished' );

    }
}
