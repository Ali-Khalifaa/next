<?php

use Illuminate\Database\Seeder;

class FollowupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Pre-Qualified',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Qualified',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Not Qualified',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Connected',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Not Connected',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Missing potential customer',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Insignificant potential customer',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Call',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Recall',
        ]);
        $followup = \App\Models\LeadsFollowup::create([
            'name' => 'Junk Lead',
        ]);
    }
}
