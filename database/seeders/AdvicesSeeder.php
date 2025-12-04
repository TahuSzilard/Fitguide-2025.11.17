<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdvicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('advices')->insert([
            [
                'category' => 'underweight',
                'content' => 'Individuals should have a carbohydrate and fat rich diet. Females should consume 2000-2200 kalories and do some light exercises. Males should consume 2200-2400 calories and start excercises with small weight. Seeds like granola and nuts are advised to eat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'normal',
                'content' => 'Individuals with this BMI level are healthy and should continue their lifestyle. To maintain this BMI level, females should consume 1700-1900 calories, while males around 1800-2000. Exercises with weights are advised in order to gain muscle mass. The diet shouldn\'t include too many carbs and fat, to avoid being overweight.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'overweight',
                'content' => 'Individuals with this BMI index are a bit above average. They should eat much less carbohydrate rich food, like meat and vegetables. Exercising is also advised. Active programmes, like cycling and jogging are a great way too lose weight fast. Under normal circumstances, females lose weight if they consume 1500-1600 calories, while males around 1600-1700. Taking up a new active hobby is also a great addition to a balanced lifestyle.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'obese',
                'content' => 'Individuals who suffer from obesity, should have a drastic change in their ways of living, if they are to lose weight. The so called fasting is great way to lose weight fast and get rid of unvanted nutritients. Exercising might not be the best solution, because they are prone to joint injury. Jogging and walking is advised, because thoose are great ways to lose weight in a short succession, while not damaging the joint. A more balanced diet is the key. Skipping dinner is also a great idea, because the fasting would be longer and eating before sleep increases body fat level.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
