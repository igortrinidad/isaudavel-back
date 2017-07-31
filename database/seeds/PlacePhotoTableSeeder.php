<?php

use App\Models\Place;
use App\Models\PlacePhoto;
use Illuminate\Database\Seeder;

class PlacePhotoTableSeeder extends Seeder
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

            PlacePhoto::create([
                'place_id' => $place,
                'path' => 'places/media/d41256e2247972a3edb20bb15f6e25dc.png',
                'is_cover' => true
            ]);

            PlacePhoto::create([
                'place_id' => $place,
                'path' => 'places/media/dc82cec6c0472da4c35dbfe628e742e3.png',
                'is_cover' => false
            ]);
        }
    }
}
