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
        $user = DB::table('users')->where('name', 'admin')->first();
        if(!$user) {
            DB::table('users')->insert([
                'name' => 'admin',
                'email' => 'admin@futureskills-sh.de',
                'password' => bcrypt('secret'),
            ]);
        }
    }
}