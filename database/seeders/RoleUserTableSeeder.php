<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        $roles = DB::table('role_user')->get();
        if ( $roles->count() == 0 ) {
            DB::table('role_user')->insert([
                'user_id' => User::getByName("admin")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('role_user')->insert([
                'user_id' => User::getByName("Katy")->id,
                'role_id' => Role::getByName("default")->id,
                'created_at' => now()
                ]);
        } else {
            echo "Role_User table not empty. Skipping...\n";
        }
    }
}
