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
                'created_at' => now()
            ]);
        }

        $katy = DB::table('users')->where('name', 'Katy')->first();
        if(!$katy) {
            DB::table('users')->insert([
                'name' => 'Katy',
                'email' => 'katy@futureskills-sh.de',
                'password' => bcrypt('secret'),
                'created_at' => now()
            ]);
        }
    }
}
