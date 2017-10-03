<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $modalities = factory(App\Models\Modality::class, 5)->create();

        foreach($modalities as $modality)
        {
            factory(\App\Models\SubModality::class, rand(3,5))->create([
                'modality_id' => $modality->id,
            ]);
        }

        $modalities = $modalities->pluck('id')->flatten()->toArray();

        factory(\App\Models\Event::class, 10)->create()->each(function($event) use($faker, $modalities){
            $event->modality_id = $faker->randomElement($modalities);
            $event->save();

            $sub_modalites = \App\Models\SubModality::where('modality_id', $event->modality_id)->get()->pluck('id')->flatten()->toArray();

            $event->sub_modalities()->attach($faker->randomElements($sub_modalites, rand(2,3)));

        });

    }
}
