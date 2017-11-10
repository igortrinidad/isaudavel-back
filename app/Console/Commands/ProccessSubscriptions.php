<?php

namespace App\Console\Commands;

use App\Mail\DefaultEmail;
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

        //Get all subscriptions 3 days before expire
        $date = Carbon::now()->addDays(3)->format('Y-m-d');

        $client_subscriptions = ClientSubscription::where('expire_at', $date)
            ->where('is_active', true)
            ->where('auto_renew', true)
            ->with('client', 'plan')
            ->get();

        $this->info('Processing '. $client_subscriptions->count() . ' subscription(s)' );

        foreach ($client_subscriptions as $client_subscription) {

            if(empty($client_subscription->workdays)){
                $this->info($client_subscription->client->full_name . ' workday vazio');
                continue;
            }

            $old_start = Carbon::createFromFormat('d/m/Y', $client_subscription->start_at);
            $old_expire = Carbon::createFromFormat('d/m/Y', $client_subscription->expire_at);

            $new_start = $old_expire->copy();
            $new_expire = $new_start->copy()->addMonths($client_subscription->plan->expiration);

            $new_invoice = Invoice::create([
                'subscription_id' => $client_subscription->id,
                'company_id' => $client_subscription->company_id,
                'value' => $client_subscription->plan->value,
                'expire_at' => $new_start->format('d/m/Y'),
                'is_confirmed' => false,
                'is_canceled' => false,
                'history' => json_decode('[]')
            ]);

            $new_schedules = [];

            //Limiteded schedules quantity
            if ($client_subscription->plan->limit_quantity) {
                for ($new_start; count($new_schedules) < $client_subscription->quantity; $new_start->addDays(1, 'days')) {

                    // get dow index
                    $dow_index = null;
                    foreach($client_subscription->workdays as $key => $workday) {

                        if($workday['dow'] == $new_start->dayOfWeek){
                            $dow_index = $key;
                        }
                    }

                    if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $new_start->dayOfWeek) {

                        $schedule_data = [
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'company_id' => $client_subscription->company_id,
                            'date' => $new_start->format('d/m/Y'),
                            'time' => $client_subscription->workdays[$dow_index]['init'],
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                            'invoice_id' => $new_invoice->id
                        ];

                        $new_schedule = Schedule::create($schedule_data);

                        $new_schedules[] = $new_schedule;

                    }

                }
            }

            //Schedules by period
            if (!$client_subscription->plan->limit_quantity) {
                for ($new_start; $new_start <= $new_expire; $new_start->addDays(1, 'days')) {

                    // get dow index
                    $dow_index = null;
                    foreach($client_subscription->workdays as $key => $workday) {

                        if($workday['dow'] == $new_start->dayOfWeek){
                            $dow_index = $key;
                        }
                    }

                    if ($dow_index > -1 && $client_subscription->workdays[$dow_index]['dow'] == $new_start->dayOfWeek) {

                        $schedule_data = [
                            'subscription_id' => $client_subscription->id,
                            'category_id' => $client_subscription->plan->category_id,
                            'company_id' => $client_subscription->company_id,
                            'date' => $new_start->format('d/m/Y'),
                            'time' => $client_subscription->workdays[$dow_index]['init'],
                            'professional_id' => $client_subscription->workdays[$dow_index]['professional_id'],
                            'invoice_id' => $new_invoice->id
                        ];

                        $new_schedule = Schedule::create($schedule_data);

                        $new_schedules[] = $new_schedule;

                    }

                }

            }

            $client_subscription->start_at = $old_start->addMonths($client_subscription->plan->expiration)->format('d/m/Y');
            $client_subscription->expire_at = $old_expire->addMonths($client_subscription->plan->expiration)->format('d/m/Y');

            $client_subscription->save();

            //New invoice Mail
            $data = [];
            $data['align'] = 'center';

            $schedules = '';

            foreach ($new_invoice->schedules as $schedule) {
                $schedules .= $schedule->date . ' ' . $schedule->time . '<br>';
            }

            $data['messageTitle'] = '<h4>Nova fatura</h4>';
            $data['messageOne'] = 'A empresa ' . $new_invoice->company->name . ' renovou seu plano de <b>' . $new_invoice->subscription->plan->name . '</b>  e uma nova fatura no valor de <b>R$' . number_format($new_invoice->value,2,',', '.'). '</b> com vencimento para <b>' . $new_invoice->expire_at . '</b> foi emitida.';
            $data['messageTwo'] = 'Confira abaixo os novos agendamentos.<br/>
            <p>Agendamentos</p>
            <b>' . $schedules . '</b>';
            $data['messageThree'] = 'Acesse online em https://isaudavel.com ou baixe o aplicativo para Android e iOS (Apple)';
            $data['messageSubject'] = 'iSaudavel: Nova fatura';

            //Send the email through the queuee
            \Mail::to($new_invoice->subscription->client->email,  $new_invoice->subscription->client->full_name)->queue(new DefaultEmail($data));

        }

        $this->info('Finished' );

    }
}
