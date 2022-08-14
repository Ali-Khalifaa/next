<?php

use Illuminate\Database\Seeder;

class MonthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month = \App\Models\Month::create([
           'name' =>'January'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'February'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'March'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'April'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'May'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'June'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'July'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'August'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'September'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'October'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'November'
        ]);
        $month = \App\Models\Month::create([
            'name' =>'December'
        ]);
    }
}
