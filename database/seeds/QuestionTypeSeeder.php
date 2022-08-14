<?php

use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\QuestionType::create([
            'name' => 'multiple choice',
            'Video' => 0,
            'audio' => 0,
            'photo' => 0,
            'article' => 0,
        ]);

        \App\Models\QuestionType::create([
            'name' => 'Video and multiple choice',
            'Video' => 1,
            'audio' => 0,
            'photo' => 0,
            'article' => 0,
        ]);

        \App\Models\QuestionType::create([
            'name' => 'Audio and multiple choice',
            'Video' => 0,
            'audio' => 1,
            'photo' => 0,
            'article' => 0,
        ]);

        \App\Models\QuestionType::create([
            'name' => 'Picture and multiple choice',
            'Video' => 0,
            'audio' => 0,
            'photo' => 1,
            'article' => 0,
        ]);

        \App\Models\QuestionType::create([
            'name' => 'Article and multiple choice',
            'Video' => 0,
            'audio' => 0,
            'photo' => 0,
            'article' => 1,
        ]);


    }
}
