<?php

use Database\Seeders\LaratrustSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LaratrustSeeder::class);
//        $this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(FollowupSeeder::class);
        $this->call(ReasonsSeeder::class);
        $this->call(CompanyFollowupSeeder::class);
        $this->call(CompanyReasonSeeder::class);
        $this->call(QuestionTypeSeeder::class);
        $this->call(DaysTableSeeder::class);
        $this->call(MonthTableSeeder::class);
        $this->call(LeadSourceSeeder::class);

    }//end of run
}//end of seeder
