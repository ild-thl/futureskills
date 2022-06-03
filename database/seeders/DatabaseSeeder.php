<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            CompetenceTableSeeder::class,
            InstitutionTableSeeder::class,
            LanguageTableSeeder::class,
            MetaTableSeeder::class,
            OfferTypeTableSeeder::class,
            PermissionTableSeeder::class,
            RoleTableSeeder::class,
            PermissionRoleTableSeeder::class,
            RoleUserTableSeeder::class,
            RolePermissionExternalCatalogSeeder::class,

            # offer as last
            OfferTableSeeder::class,
        ]);
    }
}
