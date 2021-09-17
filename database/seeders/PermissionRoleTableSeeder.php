<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        $roles = DB::table('permission_role')->get();
        if ( $roles->count() == 0 ) {
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_apikey")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_subscription")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_offer")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_institution")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_user")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_subscription")->id,
                'role_id' => Role::getByName("subscriber")->id,
                'created_at' => now()
                ]);
            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("store_update_apikey")->id,
                'role_id' => Role::getByName("institution")->id,
                'created_at' => now()
                ]);
        } else {
            echo "Permission_Role table not empty. Skipping...\n";
        }
    }
}
