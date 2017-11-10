<?php

use Illuminate\Database\Seeder;

class ClientCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $companies = \App\Models\Company::all()->pluck('id')->flatten()->toArray();

        $clients = \App\Models\Client::all();

        foreach ($clients as $client) {
            //Attach companies
            $client->companies()->attach($faker->randomElements($companies, rand(1, 3)),
                [
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

            //Avatar
            \App\Models\ClientPhoto::create([
                'client_id' => $client->id,
                'is_profile' => true,
                'is_public' => true,
                'path' => 'assets/isaudavel_holder850.png',
            ]);

            foreach ($client->companies as $company) {

                foreach ($company->plans as $plan) {


                    $calendar_settings = \App\Models\ProfessionalCalendarSetting::where('company_id', $company->id)
                        ->where('category_id', $plan->category_id)->first();

                    $workday1 = [
                        [
                            'dow' => 1,
                            'end' => '17:00',
                            'init' => '16:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 2,
                            'end' => '17:00',
                            'init' => '16:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 4,
                            'end' => '17:00',
                            'init' => '16:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 5,
                            'end' => '17:00',
                            'init' => '16:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ]
                    ];

                    $workday2 = [
                        [
                            'dow' => 1,
                            'end' => '09:00',
                            'init' => '08:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 2,
                            'end' => '10:00',
                            'init' => '09:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 4,
                            'end' => '11:00',
                            'init' => '10:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ],
                        [
                            'dow' => 5,
                            'end' => '12:00',
                            'init' => '11:00',
                            'quantity' => 3,
                            'is_limited' => true,
                            'professional_id' => $calendar_settings->professional_id
                        ]
                    ];

                    //dates
                    $start = Carbon\Carbon::now();
                    $expire = \Carbon\Carbon::now()->addDay(31);

                    //Subscription
                    $subs = \App\Models\ClientSubscription::create([
                        'company_id' => $company->id,
                        'client_id' => $client->id,
                        'plan_id' => $plan->id,
                        'value' => $plan->value,
                        'quantity' => $plan->quantity,
                        'start_at' => $start->format('d/m/Y'),
                        'expire_at' => $expire->format('d/m/Y'),
                        'auto_renew' => true,
                        'is_active' => true,
                        'workdays' => $faker->randomElement([$workday1, $workday2])
                    ]);

                    //Invoice
                    $new_invoice = \App\Models\Invoice::create([
                        'subscription_id' => $subs->id,
                        'company_id' => $subs->company_id,
                        'value' => $plan->value,
                        'expire_at' => $subs->start_at,
                        'is_confirmed' => false,
                        'is_canceled' => false,
                        'history' => json_decode('[]')
                    ]);


                    $new_schedules = [];

                    $i = 0;
                    for ($start; count($new_schedules) < $subs->quantity; $start->addDays(1, 'days')) {


                        if ($subs->workdays[$i]['dow'] == $start->dayOfWeek) {

                            $schedule_data = [
                                'subscription_id' => $subs->id,
                                'company_id' => $subs->company_id,
                                'category_id' => $plan->category_id,
                                'date' => $start->format('d/m/Y'),
                                'time' => $subs->workdays[$i]['init'],
                                'professional_id' => $subs->workdays[$i]['professional_id'],
                                'invoice_id' => $new_invoice->id
                            ];

                            $new_schedule = \App\Models\Schedule::create($schedule_data);

                            $new_schedules[] = $new_schedule;

                            $i++;

                            if ($i == count($subs->workdays)) {
                                $i = 0;
                            }

                            while ($subs->workdays[$i]['dow'] == $start->dayOfWeek && count($new_schedules) < $subs->quantity) {

                                $schedule_data['date'] = $start->format('d/m/Y');
                                $schedule_data['time'] = $subs->workdays[$i]['init'];

                                $new_schedule = Schedule::create($schedule_data);

                                $new_schedules[] = $new_schedule;

                                $i++;
                            }

                        }
                    }
                }
            }
        }
    }
}
