<?php

use Illuminate\Database\Seeder;

class OfferTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $offertypes = DB::table('offertypes')->get();
        if ( $offertypes->count() == 0 ) {
            DB::table('offertypes')->insert([
                'identifier' => 'offertypes',
                'description' => 'Online-Kurs'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'webinar',
                'description' => 'Webinar'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'presence-event',
                'description' => 'Präsenzveranstaltung'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'presence-series',
                'description' => 'Präsenzveranstaltungsreihe'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'self-study-course',
                'description' => 'Selbstlernkurs'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'course-package',
                'description' => 'Kurspaket'
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'blended-learning',
                'description' => 'Blended Learning'
                ]);
        } else {
            echo "Offertypes table not empty. Skipping...\n";        
        }
    }
}
