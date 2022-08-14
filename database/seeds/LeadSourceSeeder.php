<?php

use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\LeadSources::create([
            'name' => 'Excel Upload',
            'active' => 0,
        ]);
    }
}
