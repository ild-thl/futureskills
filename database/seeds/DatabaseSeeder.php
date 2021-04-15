<?php

use Illuminate\Database\Seeder;
use Database\Seeds\UsersTableSeeder;
use Database\Seeds\InstitutionTableSeeder;
use Database\Seeds\CompetenceTableSeeder;
use Database\Seeds\LanguageTableSeeder;
use Database\Seeds\MetaTableSeeder;
use Database\Seeds\OfferTableSeeder;
use Database\Seeds\OfferTypeTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\UsersTableSeeder::class);
        $this->call(\InstitutionTableSeeder::class);
        $this->call(\CompetenceTableSeeder::class);
        $this->call(\LanguageTableSeeder::class);
        $this->call(\MetaTableSeeder::class);
        $this->call(\OfferTypeTableSeeder::class);
        $this->call(\OfferTableSeeder::class);
    }
}
