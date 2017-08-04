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
        $this->command->info('Started database seeder');

        $this->call(CategoriesTableSeeder::class);

        /*
         * Clients
         */
        factory(App\Models\Client::class)->create([
            'name' => 'Matheus',
            'last_name' => 'Lima',
            'email' => 'me@matheuslima.com.br',
            'phone' => '(67) 99162-1584',
            'bday' => '1987-02-18',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'current_xp' => rand(3500, 5000),
            'total_xp' => rand(50000, 150000),
            'level' => rand(50, 99),
        ]);

        factory(App\Models\Client::class)->create([
            'name' => 'Igor',
            'last_name' => 'Trindade',
            'email' => 'contato@maisbartenders.com.br',
            'bday' => '1987-06-18',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'current_xp' => rand(3500, 5000),
            'total_xp' => rand(50000, 150000),
            'level' => rand(50, 99),
        ]);

        factory(App\Models\Client::class)->create([
            'name' => 'Andre',
            'last_name' => 'Brandão',
            'email' => 'andrebf4@gmail.com',
            'bday' => '1991-09-07',
            'password' => bcrypt('password'),
            'remember_token' => str_random(10),
            'current_xp' => rand(3500, 5000),
            'total_xp' => rand(50000, 150000),
            'level' => rand(50, 99),
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

        /*
        * Professionals
        */
        $this->call(ProfessionalsTableSeeder::class);

        /*
        * Companies
        */
        $this->call(CompanyTableSeeder::class);
        $this->call(CompanyCalendarSettingsTableSeeder::class);
        $this->call(ClientCompanySeeder::class);

        $this->command->info('Finished database seeder');
    }
}
