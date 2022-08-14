<?php

use Illuminate\Database\Seeder;

class ReasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reason = \App\Models\Reason::create([
           'name' => 'Send by Email',
           'leads_followup_id' => 2
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Send by Whats app',
            'leads_followup_id' => 2
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Send by SMS',
            'leads_followup_id' => 2
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Follow Up',
            'leads_followup_id' => 3
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'I cannot confirm time',
            'leads_followup_id' => 3
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Time is not fit to me',
            'leads_followup_id' => 4
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'I am not ready',
            'leads_followup_id' => 4
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Cancel for time',
            'leads_followup_id' => 5
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Cancel for financial reasons',
            'leads_followup_id' => 5
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'No Answer',
            'leads_followup_id' => 6
        ]);

        $reason = \App\Models\Reason::create([
            'name' => 'Is not available',
            'leads_followup_id' => 6
        ]);
    }
}
