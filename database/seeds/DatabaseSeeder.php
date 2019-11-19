<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{


     public function run()
     {
        $faker = Faker\Factory::create();
        User::create([
        		'name' =>'sagun',
        		'email' =>'example@gmail.com',
        		'password' => Hash::make('password')
        	]);
        foreach (range(0, 20) as $index) {
        	User::create([
        		'name' =>$faker->name,
        		'email' =>$faker->email,
        		'password' => Hash::make('password')
        	]);
        }

     }

}
