<?php

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

        $other_professionals = array_diff_assoc($other_professionals,$professionals );

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

        foreach($professionals as $professional){

            $company_name  =  $faker->company;

            $location =  $faker->randomElement($locations);

            $location['address']['name'] = $company_name;

            $lat =  $location['address']['geolocation']['lat'];
            $lng =  $location['address']['geolocation']['lng'];

            unset($location['address']['geolocation']);

            $company = \App\Models\Company::create( [
                'id' => Uuid::generate()->string,
                'owner_id' => $professional,
                'is_active' => true,
                'name' => $company_name,
                'slug' => $faker->domainName,
                'website' => $faker->domainName,
                'phone' => $faker->phoneNumber,
                'description' => $faker->text,
                'address_is_available' => true,
                'address' =>$location['address'],
                'lat'=> $lat,
                'lng'=> $lng,
                'city' => $location['city'],
                'state' => $location['state']
            ]);

            //attach admin on company
            $company->professionals()->attach($professional, ['is_admin' => true]);

            // attach other professionals (exept admins)
            $company->professionals()->attach($faker->randomElements($other_professionals, rand(1,3)));

        }

        $companies = \App\Models\Company::all();

        //Attach categories
        foreach ($companies as $company) {

            $company->categories()->attach($faker->randomElements($categories, (rand(1,3))));

            //Avatar
            \App\Models\CompanyPhoto::create([
                'company_id' => $company->id,
                'is_profile' => true,
                'path' => 'assets/isaudavel_holder850.png',
            ]);


            //Rating
            $clients_rating = $faker->randomElements($clients, rand(1,3));
            foreach ($clients_rating as $client) {

                \App\Models\CompanyRating::create([
                    'client_id' => $client,
                    'company_id' => $company->id,
                    'rating' => rand(1,5),
                    'content' => $faker->sentence(15)
                ]);

            }
        }



    }
}
