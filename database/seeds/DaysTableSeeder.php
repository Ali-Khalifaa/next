<?php

use Illuminate\Database\Seeder;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $day = \App\Models\Day::create([
            'day' => "Saturday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Sunday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Monday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Tuesday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Wednesday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Thursday"
        ]);

        $day = \App\Models\Day::create([
            'day' => "Friday"
        ]);
    }
}
