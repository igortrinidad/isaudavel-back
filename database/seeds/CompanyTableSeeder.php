<?php

use Illuminate\Database\Seeder;

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

        $professionals = \App\Models\Professional::all()->pluck('id');

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

        foreach($professionals as $professional){

            $company_name  =  $faker->company;

            $company_category = $faker->randomElement($company_categories);

            $location =  $faker->randomElement($locations);

            $location['address']['name'] = $company_name;

            \App\Models\Company::create( [
                'owner_id' => $professional,
                'is_active' => true,
                'name' => $company_name,
                'website' => $faker->domainName,
                'phone' => $faker->phoneNumber,
                'address_is_available' => true,
                'address' =>$location['address'],
                'city' => $location['city'],
                'state' => $location['state'],
                'price' => rand(400, 700),
                'is_pilates' => $company_category == 'is_pilates' ? true : false,
                'is_personal' => $company_category == 'is_personal' ? true : false,
                'is_physio' => $company_category == 'is_physio' ? true : false,
                'is_nutrition' => $company_category == 'is_nutrition' ? true : false,
                'is_massage' => $company_category == 'is_massage' ? true : false,
                'is_healthy' => $company_category == 'is_healthy' ? true : false,
                'rating' => rand(3, 5),
                'informations' => json_decode('[]'),
                'advance_schedule' => 24,
                'advance_reschedule' => rand(2, 3),
                'points_to_earn_bonus' => rand(300, 500),
            ]);

        }

    }
}
