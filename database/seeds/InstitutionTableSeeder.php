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
        $institution = DB::table('institutions')->where('Title', 'FutureSkills')->first();
        if(!$institution) {
            DB::table('institutions')->insert([
                'title' => 'FutureSkills',
                'url' => 'https://www.futureskills-sh.de'
            ]);
        }
    }
}