<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = DB::table('languages')->get();
        if ( $languages->count() == 0 ) {
            DB::table('languages')->insert([
                'identifier' => 'de',
                'description' => 'Deutsch',
                'created_at' => now()
                ]);
            DB::table('languages')->insert([
                'identifier' => 'en',
                'description' => 'Englisch',
                'created_at' => now()
                ]);
        } else {
            echo "Languages table not empty. Skipping...\n";
        }
    }
}
