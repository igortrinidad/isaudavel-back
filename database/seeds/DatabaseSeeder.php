<?php

use Carbon\Carbon;
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

        // Runs seeds only in local environment
        if(\App::environment('local')){

            $terms = ['accepted' => true, 'accepted_at' => Carbon::now()->format('d/m/Y H:i:s')];

            /*
             * Clients
             */
            factory(App\Models\Client::class)->create([
                'name' => 'Matheus',
                'last_name' => 'Lima',
                'email' => 'me@matheuslima.com.br',
                'slug' => str_random(10),
                'phone' => '(67) 99162-1584',
                'bday' => '1987-02-18',
                'password' => bcrypt('password'),
                'remember_token' => str_random(10),
                'current_xp' => rand(3500, 5000),
                'total_xp' => rand(50000, 150000),
                'level' => rand(50, 99),
                'terms' => $terms
            ]);

            factory(App\Models\Client::class)->create([
                'name' => 'Igor',
                'last_name' => 'Trindade',
                'email' => 'igorlucast@hotmail.com',
                'slug' => str_random(10),
                'bday' => '1987-06-18',
                'password' => bcrypt('password'),
                'remember_token' => str_random(10),
                'current_xp' => rand(3500, 5000),
                'total_xp' => rand(50000, 150000),
                'level' => rand(50, 99),
                'terms' => $terms
            ]);

            factory(App\Models\Client::class)->create([
                'name' => 'Andre',
                'last_name' => 'Brandão',
                'email' => 'andrebf4@gmail.com',
                'slug' => str_random(10),
                'bday' => '1991-09-07',
                'password' => bcrypt('password'),
                'remember_token' => str_random(10),
                'current_xp' => rand(3500, 5000),
                'total_xp' => rand(50000, 150000),
                'level' => rand(50, 99),
                'terms' => $terms
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
                'email' => 'igorlucast@hotmail.com',
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


            /*
            * Clients
            */
            $this->call(ActivitiesTableSeeder::class);
            $this->call(TrainningsTableSeeder::class);
            $this->call(ClientEvaluationsTableSeeder::class);
            $this->call(ClientRestrictionsTableSeeder::class);
            $this->call(ClientExamsTableSeeder::class);

            /*
             * Meal recipes
             */
            $this->call(MealRecipesTableSeeder::class);

            /*
             * Events
             */

            $this->call(EventsTableSeeder::class);

            /*
             * Professional without company
             */
            $this->call(ProfessionalSeeder::class);

        }
        $this->command->info('Finished database seeder');
    }
}
