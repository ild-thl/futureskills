<?php
namespace database\seeds;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\User;



class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    //Institution::getById($institutionId)->api_keys->count()
    #store_update_apikey
    #store_update_subscription
    #store_update_offer
    #store_update_institution
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
