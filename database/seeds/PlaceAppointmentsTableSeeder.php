<?php

use App\Models\Place;
use App\Models\PlaceAppointment;
use Illuminate\Database\Seeder;

class PlaceAppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $colors = ["#1abc9c", "#2ecc71", "#3498db", "#9b59b6", "#34495e", "#16a085", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50", "#f1c40f", "#e67e22", "#e74c3c", "#ecf0f1", "#95a5a6", "#f39c12", "#d35400", "#c0392b", "#bdc3c7", "#7f8c8d"];

        $places = Place::get()->pluck('id')->all();

        foreach ($places as $key => $place) {

            $key =  (integer) $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addDay(1)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);

            $key = $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addDay(1)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);

            $key = $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addDay(1)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);

            $key = $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => false,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addHours(8)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);

            $key = $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addDay(1)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);

            $key = $key + 1;
            $color = $faker->randomElement($colors);
            $date = $faker->dateTimeBetween($startDate = '-15 days', $endDate = 'now')->format('Y-m-d h:m:s');
            PlaceAppointment::create([
                'place_id' => $place,
                'title' => 'Evento ' . $key,
                'allDay' => true,
                'start' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->toDateTimeString(),
                'end' => \Carbon\Carbon::createFromFormat('Y-m-d h:m:s', $date)->addDay(1)->toDateTimeString(),
                'backgroundColor' => $color,
                'borderColor' => $color
            ]);
        }


    }
}
