<?php

use Illuminate\Database\Seeder;

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
        var_dump($institutions);
        if ( $institutions->count() == 0 ) {
            DB::table('institutions')->insert([
                'title' => 'FutureSkills',
                'url' => 'https://www.futureskills-sh.de'
            ]);
        } else {
            echo "Institutions table not empty. Skipping...\n";
        }
    }
}