<?php

use Illuminate\Database\Seeder;

class MetaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {        
        $metas = DB::table('metas')->get();
        if ( $metas->count() == 0 ) {
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'sponsor'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'exam'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'text',
                'description' => 'requirements'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'niveau'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'location'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'int(11)',
                'description' => 'ects'
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'time_requirement'
                ]);
        } else {
            echo "Metas table not empty. Skipping...\n";        
        }
    }
}
