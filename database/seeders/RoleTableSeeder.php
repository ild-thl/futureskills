<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = DB::table('roles')->get();
        if ( $roles->count() == 0 ) {
            DB::table('roles')->insert([
                'role' => 'admin',
                'created_at' => now()
                ]);
            DB::table('roles')->insert([
                'role' => 'institution',
                'created_at' => now()
                ]);
            DB::table('roles')->insert([
                'role' => 'subscriber',
                'created_at' => now()
                ]);
            DB::table('roles')->insert([
                'role' => 'default',
                'created_at' => now()
                    ]);
        } else {
            echo "Roles table not empty. Skipping...\n";
        }
    }
}
