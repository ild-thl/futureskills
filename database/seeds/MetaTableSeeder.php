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
                'description' => 'sponsor',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'exam',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'text',
                'description' => 'requirements',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'niveau',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'location',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'int(11)',
                'description' => 'ects',
                'created_at' => now()
                ]);
            DB::table('metas')->insert([
                'datatype' => 'varchar(191)',
                'description' => 'time_requirement',
                'created_at' => now()
                ]);
        } else {
            echo "Metas table not empty. Skipping...\n";        
        }
    }
}
