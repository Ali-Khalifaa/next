<?php

use Illuminate\Database\Seeder;

class CompanyFollowupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'qualified',
            'probability'=> 10,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'Technical offer',
            'probability'=> 20,

        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'Financial offer',
            'probability'=> 40,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'negotiation',
            'probability'=> 60,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'Financial offer 2',
            'probability'=> 75,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'approval',
            'probability'=> 90,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'PO',
            'probability'=> 100,
        ]);
        $followup = \App\Models\CompanyFollowup::create([
            'name' => 'not intersted',
            'probability'=> 0,
        ]);
    }
}
