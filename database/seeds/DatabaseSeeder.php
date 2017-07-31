<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(App\Models\User::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);


        factory(App\Models\User::class)->create([
            'name' => 'Igor',
            'last_name' => 'Trindade',
            'email' => 'contato@maisbartenders.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\User::class)->create([
            'name' => 'Andre',
            'last_name' => 'Brandão',
            'email' => 'andrebf4@gmail.com',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\User::class, 10)->create();

        factory(App\Models\Place::class, 50)->create();

        $this->call(PlaceCallendarSettingsTableSeeder::class);

        $this->call(PlacePhotoTableSeeder::class);

        $this->call(PlaceAppointmentsTableSeeder::class);

        factory(App\Models\Client::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'phone' => '(67) 99162-1584',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\Client::class, 10)->create();

        /*
         * Oracle users
         */
        factory(App\Models\OracleUser::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\OracleUser::class)->create([
            'name' => 'Igor',
            'last_name' => 'Trindade',
            'email' => 'contato@maisbartenders.com.br',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);

        factory(App\Models\OracleUser::class)->create([
            'name' => 'Andre',
            'last_name' => 'Brandão',
            'email' => 'andrebf4@gmail.com',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
        ]);
    }
}
