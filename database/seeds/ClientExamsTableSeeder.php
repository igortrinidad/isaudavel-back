<?php

use Illuminate\Database\Seeder;

class ClientExamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('pt_BR');

        $professionals = \App\Models\Professional::all()->pluck('id')->flatten()->toArray();
        $clients = \App\Models\Client::all()->pluck('id')->flatten()->toArray();

        foreach ($clients as $client) {
            \App\Models\Exam::create([
                'client_id' => $client,
                'type' => 'image',
                'created_by_id' => $faker->randomElement($professionals),
                'created_by_type' => \App\Models\Professional::class,
                'observation' => 'Machucou um pouco, nada muito sério... nem precisou amputar.'
            ]);
        }

        $exams = \App\Models\Exam::all()->pluck('id')->flatten()->toArray();


        foreach ($exams as $exam) {
            \App\Models\ExamAttachment::create([
                'exam_id' => $exam,
                'path' => 'exam/attachment/96df6ab0d2bd471121a336d10e28789e.jpg',
                'filename' => 'x-ray.jpg',
                'extension' => 'jpg'
            ]);
        }
    }
}
