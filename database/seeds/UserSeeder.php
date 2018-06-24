<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([[
            'id' => 1,
            'full_name' => 'Admin Crysleep',
            'email' => 'admin@cryosleep.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'referral_code' => str_random(16) . 'AC',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime()
        ]]);
    }
}
