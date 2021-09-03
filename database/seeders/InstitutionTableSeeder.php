<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitutionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $institutions = DB::table('institutions')->get();
        if ( $institutions->count() == 0 ) {
            DB::table('institutions')->insert([
                'title' => 'FutureSkills',
                'url' => 'https://www.futureskills-sh.de',
                'created_at' => now()
            ]);
        } else {
            echo "Institutions table not empty. Skipping...\n";
        }
    }
}
