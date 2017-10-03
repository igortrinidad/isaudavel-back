<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Place;
use App\Models\PlaceCategory;
use App\Models\User;
use Carbon\Carbon;
use Webpatser\Uuid\Uuid;

$faker = \Faker\Factory::create('pt_BR');


$factory->define(App\Models\Professional::class, function (Faker\Generator $faker) {
    static $password;
    $terms = ['accepted' => true, 'accepted_at' => Carbon::now()->format('d/m/Y H:i:s')];
    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'slug' => str_random(10),
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => str_random(10),
        'terms' => $terms
    ];
});



$factory->define(App\Models\Client::class, function () use($faker){
    static $password;
    $terms = ['accepted' => true, 'accepted_at' => Carbon::now()->format('d/m/Y H:i:s')];

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'slug' => str_random(10),
        'password' => $password ?: $password = bcrypt('password'),
        'phone' => $faker->cellphoneNumber,
        'bday' => $faker->dateTimeBetween($startDate = '-40 years', $endDate ='-18 years'),
        'remember_token' => str_random(10),
        'current_xp' => rand(3500, 5000),
        'total_xp' => rand(50000, 150000),
        'level' => rand(50, 99),
        'terms' => $terms
    ];
});

$factory->define(App\Models\OracleUser::class, function () use($faker){
    static $password;

    return [
        'name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('password'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\CompanyCalendarSettings::class, function () use($faker){

    return [
        'company_id' => '',
        'calendar_is_public' => false,
        'calendar_is_active' => false,
        'workday_is_active' => false,
        'advance_schedule' => 0,
        'advance_reschedule' => 0,
        'points_to_earn_bonus' => 0,
        'available_dates_range' => json_decode('[]'),
        'available_days_config' => json_decode('[{"name":"sunday","label":"Domingo","day_of_week":0,"hour":null,"allday":true,"unavailable":false},{"name":"monday","label":"Segunda-feira","day_of_week":1,"hour":null,"allday":true,"unavailable":false},{"name":"tuesday","label":"Terça-feira","day_of_week":2,"hour":null,"allday":true,"unavailable":false},{"name":"wednesday","label":"Quarta-feira","day_of_week":3,"hour":null,"allday":true,"unavailable":false},{"name":"thursday","label":"Quinta-feira","day_of_week":4,"hour":null,"allday":true,"unavailable":false},{"name":"friday","label":"Sexta-feira","day_of_week":5,"hour":null,"allday":true,"unavailable":false},{"name":"saturday","label":"Sábado","day_of_week":6,"hour":null,"allday":true,"unavailable":false}]'),
    ];
});

$factory->define(App\Models\MealRecipe::class, function () use($faker){

    $created_by_id = null;
    $created_by_type = null;

    $faker = \Faker\Factory::create('pt_BR');

    $types = \App\Models\MealType::all()->pluck('id')->flatten()->toArray();


    $creator_type = $faker->randomElement(['professional', 'client']);

    if($creator_type == 'professional'){

        $professional = \App\Models\Professional::inRandomOrder()->first();
        $created_by_id = $professional->id;
        $created_by_type = \App\Models\Professional::class;
    }else{
        $client = \App\Models\Client::inRandomOrder()->first();
        $created_by_id = $client->id;
        $created_by_type = \App\Models\Client::class;
    }
    $ingredients = [
        [
            'description' => rand(1, 5).' '.$faker->words(2, true),
        ],
        [
            'description' => rand(1, 5).' '.$faker->words(2, true),
        ],
        [
            'description' => rand(1, 5).' '.$faker->words(2, true),
        ],
        [
            'description' => rand(1, 5).' '.$faker->words(2, true),
        ],
        [
            'description' => rand(1, 5).' '.$faker->words(2, true),
        ]
    ];

    return [
        'id' => Uuid::generate()->string,
        'type_id' => $faker->randomElement($types),
        'title' => $faker->words(6, true),
        'prep_time' => rand(10,60),
        'portions' => rand(1,10),
        'portion_size' => '200g',
        'difficulty' => rand(1,3),
        'prep_description' => $faker->paragraph(5),
        'ingredients' => $ingredients,
        'kcal' => rand(100,1000),
        'protein'=> rand(100,1000),
        'carbohydrate'=> rand(100,1000),
        'lipids' => rand(100,1000),
        'fiber' => rand(100,1000),
        'created_by_id' => $created_by_id,
        'created_by_type' => $created_by_type,
    ];
});

$factory->define(App\Models\MealRecipeTag::class, function () use($faker){

    $name = $faker->words(rand(1,2), true);
    return [
        'name' => ucfirst($name),
        'slug' => str_slug($name)
    ];
});

$factory->define(App\Models\Modality::class, function () use($faker){

    $name = $faker->words(rand(1,2), true);
    return [
        'name' => ucfirst($name),
        'slug' => str_slug($name)
    ];
});

$factory->define(App\Models\SubModality::class, function () use($faker){

    $name = $faker->words(rand(1,2), true);
    return [
        'name' => ucfirst($name),
        'slug' => str_slug($name)
    ];
});




$factory->define(App\Models\Event::class, function () use($faker){

    $name = $faker->words(rand(1,3), true);

    $created_by_id = null;
    $created_by_type = null;

    $creator_type = $faker->randomElement(['professional', 'client']);

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

    if($creator_type == 'professional'){

        $professional = \App\Models\Professional::inRandomOrder()->first();
        $created_by_id = $professional->id;
        $created_by_type = \App\Models\Professional::class;
    }else{
        $client = \App\Models\Client::inRandomOrder()->first();
        $created_by_id = $client->id;
        $created_by_type = \App\Models\Client::class;
    }

    $location = $faker->randomElement($locations);

    $location['address']['name'] = $name;

    $lat = $location['address']['geolocation']['lat'];
    $lng = $location['address']['geolocation']['lng'];

    unset($location['address']['geolocation']);

    return [
        'id' => Uuid::generate()->string,
        'name' => ucfirst($name),
        'slug' => str_slug($name),
        'description' => $faker->paragraph(5),
        'address' => $location['address'],
        'lat' => $lat,
        'lng' => $lng,
        'city' => $location['city'],
        'state' => $location['state'],
        'is_free' => true,
        'value' => 0,
        'date' => $faker->dateTimeBetween($startDate = 'now', $endDate ='+3 weeks'),
        'time' => $faker->randomElement(['09:00', '10:00', '11:00','15:00', '16:00' ]),
        'created_by_id' => $created_by_id,
        'created_by_type' => $created_by_type,
        'is_published' => true
    ];
});


