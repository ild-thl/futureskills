<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
use App\Models\Role;

class RolePermissionExternalCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!Permission::where('name', '=', 'update_external_catalogs')->exists()) {

            DB::table('permissions')->insert([
                'name' => 'update_external_catalogs',
                'created_at' => now()
                ]);

            DB::table('permission_role')->insert([
                'permission_id' => Permission::getByName("update_external_catalogs")->id,
                'role_id' => Role::getByName("admin")->id,
                'created_at' => now()
                ]);
        }else {
            echo "Permission already exists. Skipping...\n";
    }
    }
}
