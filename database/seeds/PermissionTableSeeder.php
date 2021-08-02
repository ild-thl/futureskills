<?php
namespace database\seeds;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = DB::table('permissions')->get();
        if ( $roles->count() == 0 ) {
            DB::table('permissions')->insert([
                'name' => 'store_update_apikey',
                'created_at' => now()
                ]);
            DB::table('permissions')->insert([
                'name' => 'store_update_offer',
                'created_at' => now()
                ]);
            DB::table('permissions')->insert([
                'name' => 'store_update_institution',
                'created_at' => now()
                ]);
            DB::table('permissions')->insert([
                'name' => 'store_update_subscription',
                'created_at' => now()
                ]);
            DB::table('permissions')->insert([
                'name' => 'store_update_user',
                'created_at' => now()
                ]);
        } else {
            echo "Permissions table not empty. Skipping...\n";
        }
    }
}
