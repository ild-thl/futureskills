<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'description' => 'Tech-Kurse',
                'created_at' => now()
                ]);
            DB::table('competences')->insert([
                'identifier' => 'digital',
                'description' => 'Digital Basic-Kurse',
                'created_at' => now()
                ]);
            DB::table('competences')->insert([
                'identifier' => 'classic',
                'description' => 'Classic-Kurse',
                'created_at' => now()
                ]);
        } else {
            echo "Competences table not empty. Skipping...\n";
        }
    }
}
