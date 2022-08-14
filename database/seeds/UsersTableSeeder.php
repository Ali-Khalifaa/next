<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'email' => 'super_admin@app.com',
            'type' => 'employee',
            'password' => bcrypt('secret'),
        ]);

        $employee = \App\Models\Employee::create([
            'first_name'=>'super',
            'middle_name'=>'admin',
            'last_name'=>'admin',
            'phone'=>'123456',
            'address'=>'giza',
            'National_ID'=>'123456789123',
            'mobile'=>'01122334455',
            'birth_date'=>'2021-9-19',
            'hiring_date'=>'2021-9-19',
            'img'=>'admin00100.png',
            'user_id'=>$user->id,
            'has_account'=>1,
            'active'=>1,
            'admin'=>1,

        ]);

        $user->attachRole('super_admin');
    }//end of run
}//end of seeder
