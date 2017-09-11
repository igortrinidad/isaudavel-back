<?php

use App\Models\CompanyInvoice;
use App\Models\CompanySubscription;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all()->take(10)->pluck('id')->flatten()->toArray();

        $other_professionals = \App\Models\Professional::all()->pluck('id')->flatten()->toArray();

        $other_professionals = array_diff_assoc($other_professionals, $professionals);

        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        $faker = \Faker\Factory::create('pt_BR');

        $categories = \App\Models\Category::where('slug', '<>', 'all')->get()->pluck('id')->flatten()->toArray();

        $company_categories = [
            'is_pilates',
            'is_personal',
            'is_physio',
            'is_nutrition',
            'is_massage',
            'is_healthy'
        ];

        $locations = [
            ['city' => 'Rio de Janeiro', 'state' => 'RJ',
                'address' => json_decode('{"url": "https://maps.google.com/?q=Rio+de+Janeiro,+RJ,+Brasil&ftid=0x9bde559108a05b:0x50dc426c672fd24e", "name": "Rio de Janeiro", "geolocation": {"lat": -22.9068467, "lng": -43.17289649999998}, "full_address": "Rio de Janeiro, RJ, Brasil"}', true)
            ],
            ['city' => 'Belo Horizonte', 'state' => 'MG',
                'address' => json_decode('{"url": "https://maps.google.com/?q=Belo+Horizonte,+MG,+Brasil&ftid=0xa690cacacf2c33:0x5b35795e3ad23997", "name": "Belo Horizonte", "geolocation": {"lat": -19.9166813, "lng": -43.9344931}, "full_address": "Belo Horizonte, MG, Brasil"}', true)
            ],
            ['city' => 'São Paulo', 'state' => 'SP',
                'address' => json_decode('{"url": "https://maps.google.com/?q=S%C3%A3o+Paulo,+SP,+Brasil&ftid=0x94ce448183a461d1:0x9ba94b08ff335bae", "name": "São Paulo", "geolocation": {"lat": -23.5505199, "lng": -46.63330940000003}, "full_address": "São Paulo, SP, Brasil"}', true)
            ],
            ['city' => 'Campo Grande', 'state' => 'MS',
                'address' => json_decode('{"url": "https://maps.google.com/?q=Campo+Grande,+MS,+Brasil&ftid=0x9486e6726b2b9f27:0xf5a8469ebc84d2c1", "name": "Campo Grande", "geolocation": {"lat": -20.4697105, "lng": -54.620121100000006}, "full_address": "Campo Grande, MS, Brasil"}', true)
            ],
        ];

        foreach ($professionals as $professional) {

            $company_name = $faker->company;

            $location = $faker->randomElement($locations);

            $location['address']['name'] = $company_name;

            $lat = $location['address']['geolocation']['lat'];
            $lng = $location['address']['geolocation']['lng'];

            unset($location['address']['geolocation']);

            $terms = ['accepted' => true, 'accepted_at' => Carbon::now()->format('d/m/Y H:i:s')];

            $company = \App\Models\Company::create([
                'id' => Uuid::generate()->string,
                'owner_id' => $professional,
                'is_active' => true,
                'name' => $company_name,
                'slug' => str_slug($company_name, '-'),
                'website' => $faker->domainName,
                'phone' => $faker->phoneNumber,
                'description' => $faker->text,
                'address_is_available' => true,
                'address' => $location['address'],
                'lat' => $lat,
                'lng' => $lng,
                'city' => $location['city'],
                'state' => $location['state'],
                'terms' => $terms
            ]);

            //attach admin on company
            $company->professionals()->attach($professional, [
                'is_admin' => true,
                'is_confirmed' => true,
                'is_public' => true,
                'confirmed_by_id' => $professional,
                'confirmed_by_type' => \App\Models\Professional::class,
                'confirmed_at' => Carbon::now()
            ]);

            // attach other professionals (exept admins)
            $company->professionals()->attach($faker->randomElement($other_professionals), [
                'is_confirmed' => true,
                'is_public' => true,
                'confirmed_by_id' => $professional,
                'confirmed_by_type' => \App\Models\Professional::class,
                'confirmed_at' => Carbon::now()
            ]);


        }

        $companies = \App\Models\Company::all();

        //Attach categories
        foreach ($companies as $company) {

            $categories_new  = [];

            foreach($company->professionals as $professional){
                $professional_categories = $professional->categories->pluck('id')->flatten()->toArray();

                $categories_new[] = $professional_categories;

                //Professional Calendar Settings
                foreach ($professional_categories as $professional_category){
                    \App\Models\ProfessionalCalendarSetting::create([
                        'company_id' => $company->id,
                        'category_id' => $professional_category,
                        'professional_id' => $professional->id,
                        'is_active' => true,
                        'slot_duration' => 60,
                        'workdays' => json_decode('[{"dow": 1, "end": "09:00", "init": "08:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "10:00", "init": "09:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "11:00", "init": "10:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "12:00", "init": "11:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "13:00", "init": "12:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "14:00", "init": "13:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "15:00", "init": "14:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "16:00", "init": "15:00", "quantity": 3, "is_limited": true}, {"dow": 1, "end": "17:00", "init": "16:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "09:00", "init": "08:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "10:00", "init": "09:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "11:00", "init": "10:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "12:00", "init": "11:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "13:00", "init": "12:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "14:00", "init": "13:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "15:00", "init": "14:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "16:00", "init": "15:00", "quantity": 3, "is_limited": true}, {"dow": 2, "end": "17:00", "init": "16:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "09:00", "init": "08:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "10:00", "init": "09:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "11:00", "init": "10:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "12:00", "init": "11:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "13:00", "init": "12:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "14:00", "init": "13:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "15:00", "init": "14:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "16:00", "init": "15:00", "quantity": 3, "is_limited": true}, {"dow": 3, "end": "17:00", "init": "16:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "09:00", "init": "08:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "10:00", "init": "09:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "11:00", "init": "10:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "12:00", "init": "11:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "13:00", "init": "12:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "14:00", "init": "13:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "15:00", "init": "14:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "16:00", "init": "15:00", "quantity": 3, "is_limited": true}, {"dow": 4, "end": "17:00", "init": "16:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "09:00", "init": "08:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "10:00", "init": "09:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "11:00", "init": "10:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "12:00", "init": "11:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "13:00", "init": "12:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "14:00", "init": "13:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "15:00", "init": "14:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "16:00", "init": "15:00", "quantity": 3, "is_limited": true}, {"dow": 5, "end": "17:00", "init": "16:00", "quantity": 3, "is_limited": true}]')
                    ]);
                }

            }

            $categories_new = array_unique(array_flatten($categories_new));

            $company->categories()->attach($categories_new);

            $subscription_total  = ($company->categories->count() * 37.90) + (($company->professionals->count() - 1) * 17.90);

            //Company Subscription
            $company_subscription = CompanySubscription::create([
                'company_id' => $company->id,
                'professionals' => $company->professionals->count(),
                'categories' => $company->categories->count(),
                'total' => $subscription_total,
                'is_active' => true,
                'start_at' => Carbon::now()->format('d/m/Y'),
                'expire_at' => Carbon::now()->addMonth(1)->format('d/m/Y')
            ]);

            // Company Invoice
            $invoice_items = [
                [
                    'description' => 'Especialidades da empresa',
                    'item' => 'categories',
                    'quantity' => $company->categories->count(),
                    'total' => ($company->categories->count() * 37.90) ,
                    'is_partial' => false,
                    'reference' => 'Referente ao período de '.  Carbon::now()->format('d/m/Y').' à '.Carbon::now()->addMonth(1)->format('d/m/Y')
                ],
                [
                    'description' => 'Profissionais da empresa',
                    'item' => 'professionals',
                    'quantity' => $company->professionals->count(),
                    'total' => (($company->professionals->count() - 1) * 17.90),
                    'is_partial' => false,
                    'reference' => 'Referente ao período de '.  Carbon::now()->format('d/m/Y').' à '.Carbon::now()->addMonth(1)->format('d/m/Y')
                ],

            ];

            $invoice_history = [
                [
                    'full_name' =>'Sistema iSaudavel',
                    'action' => 'invoice-created',
                    'label' => 'Fatura gerada',
                    'date' => Carbon::now()->format('Y-m-d H:i:s')
                ]
            ];

            $invoice = CompanyInvoice::create([
                'company_id' => $company->id,
                'subscription_id' => $company_subscription->id,
                'total' => $company_subscription->total,
                'expire_at' => $company_subscription->expire_at,
                'items' => $invoice_items,
                'history' => $invoice_history,
            ]);

            foreach($company->categories as $category){
                //Category Calendar Settings
                \App\Models\CategoryCalendarSetting::create([
                    'company_id' => $company->id,
                    'category_id' => $category->id,
                    'is_professional_scheduled' => true,
                    'workdays' => json_decode('[]')
                ]);

                //Plan
                \App\Models\Plan::create([
                    'id' => Uuid::generate()->string,
                    'company_id' => $company->id,
                    'category_id' => $category->id,
                    'name' => 'Plano '.$category->name,
                    'label' => 'aula',
                    'description' => $faker->sentence(10),
                    'value' => 500,
                    'expiration' => 1,
                    'limit_quantity' => true,
                    'quantity' => 8,
                    'is_starred' => true,
                    'is_active' => true
                ]);
            }

            //Avatar
            \App\Models\CompanyPhoto::create([
                'company_id' => $company->id,
                'is_profile' => true,
                'path' => 'assets/isaudavel_holder850.png',
            ]);


            //Rating
            $clients_rating = $faker->randomElements($clients, rand(1, 3));
            foreach ($clients_rating as $client) {

                \App\Models\CompanyRating::create([
                    'client_id' => $client,
                    'company_id' => $company->id,
                    'rating' => rand(1, 5),
                    'content' => $faker->sentence(15)
                ]);

            }

            $professionals_recomendations = $faker->randomElements($other_professionals, rand(1, 3));

            foreach ($professionals_recomendations as $professional) {

                \App\Models\Recomendation::create([
                    'from_id' => $professional,
                    'from_type' => \App\Models\Professional::class,
                    'to_id' => $company->id,
                    'to_type' => \App\Models\Company::class,
                    'content' => $faker->sentence(15)
                ]);

            }
        }


    }
}
