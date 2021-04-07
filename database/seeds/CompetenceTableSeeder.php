<?php

use Illuminate\Database\Seeder;

class CompetenceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $competences = DB::table('competences')->get();
        if ( $competences->count() == 0 ) {
            DB::table('competences')->insert([
                'identifier' => 'tech',
                'description' => 'Tech-Kurse'
                ]);
            DB::table('competences')->insert([
                'identifier' => 'digital',
                'description' => 'Digital Basic-Kurse'
                ]);
            DB::table('competences')->insert([
                'identifier' => 'classic',
                'description' => 'Classic-Kurse'
                ]);
        } else {
            echo "Competences table not empty. Skipping...\n";        
        }
    }
}
