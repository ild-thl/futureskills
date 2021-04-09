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
                'description' => 'Online-Kurs',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'webinar',
                'description' => 'Webinar',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'presence-event',
                'description' => 'Präsenzveranstaltung',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'presence-series',
                'description' => 'Präsenzveranstaltungsreihe',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'self-study-course',
                'description' => 'Selbstlernkurs',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'course-package',
                'description' => 'Kurspaket',
                'created_at' => now()
                ]);
            DB::table('offertypes')->insert([
                'identifier' => 'blended-learning',
                'description' => 'Blended Learning',
                'created_at' => now()
                ]);
        } else {
            echo "Offertypes table not empty. Skipping...\n";        
        }
    }
}
