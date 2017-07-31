<?php

use App\Models\Place;
use Illuminate\Database\Seeder;

class PlaceCallendarSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = Place::get()->pluck('id')->all();

        foreach ($places as $place) {

            \App\Models\PlaceCalendarSettings::create([
                'place_id' => $place,
                'available_dates_range' => json_decode('[]'),
                'available_days_config' => json_decode('[{"name":"sunday","label":"Domingo","day_of_week":0,"hour":null,"allday":true,"unavailable":false},{"name":"monday","label":"Segunda-feira","day_of_week":1,"hour":null,"allday":true,"unavailable":false},{"name":"tuesday","label":"Terça-feira","day_of_week":2,"hour":null,"allday":true,"unavailable":false},{"name":"wednesday","label":"Quarta-feira","day_of_week":3,"hour":null,"allday":true,"unavailable":false},{"name":"thursday","label":"Quinta-feira","day_of_week":4,"hour":null,"allday":true,"unavailable":false},{"name":"friday","label":"Sexta-feira","day_of_week":5,"hour":null,"allday":true,"unavailable":false},{"name":"saturday","label":"Sábado","day_of_week":6,"hour":null,"allday":true,"unavailable":false}]')
            ]);

        }
    }
}
