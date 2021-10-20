<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Competence;
use App\Models\Meta;
use App\Models\Offer;
use App\Models\Language;
use App\Models\Offertype;
use Illuminate\Support\Facades\DB;

class OfferTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $offers = DB::table('offers')->get();
        if ( $offers->count() == 0 ) {

            $languages = Language::all();
            $language_ids = array();
            foreach( $languages as $language ) {
                $language_ids[] = $language->id;
            }
            $offertypes = Offertype::all();
            $offertype_ids = array();
            foreach( $offertypes as $offertype ) {
                $offertype_ids[] = $offertype->id;
            }

            $i = 1;
            while ( $i <= 10 ) {
            DB::table('offers')->insert([
                'title' => 'Testkurs '.$i,
                'description' => 'Beschreibung zu Testkurs '.$i,
                'image_path' => '/assets/images/FutureSkills_Example_Image.png',
                'institution_id' => 1,
                'subtitle' => 'Kurzbeschreibung zu Testkurs '.$i,
                'hashtag' => '#TESTKURS'.$i,
                'author' => 'Autor:in von Testkurs '.$i,
                'target_group' => 'Zielgruppe von Testkurs '.$i,
                'url' => 'https://www.futureskills-sh.de/offer/'.$i,
                'offertype_id' => $offertype_ids[array_rand($offertype_ids, 1)],
                'language_id' => $language_ids[array_rand($language_ids, 1)],
                'externalId' => null,
                'created_at' => now()
                ]);
                $i++;
            }

            $competences = Competence::all();
            $competence_ids = array();
            foreach( $competences as $competence ) {
                $competence_ids[] = $competence->id;
            }
            $metas = Meta::all();
            $meta_ids = array();
            foreach( $metas as $meta ) {
                $meta_ids[] = $meta->id;
            }
            $offers = Offer::all();
            foreach( $offers as $offer ) {

                $comp_sync = array();
                $competence_keys = array_rand( $competence_ids, random_int ( 1, count( $competence_ids ) ) );
                $competence_keys = is_array($competence_keys) ? $competence_keys : [ $competence_keys ];
                foreach ( $competence_keys as $key => $val ) {
                    $comp_sync[] = $competence_ids[$val];
                }
                $offer->competences()->sync( $comp_sync );

                $meta_sync = array();
                $meta_keys = array_rand( $meta_ids, random_int ( 1, count( $meta_ids ) ) );
                $meta_keys = is_array($meta_keys) ? $meta_keys : [ $meta_keys ];
                foreach ( $meta_keys  as $key => $val ) {
                    $meta_sync[] = $meta_ids[$val];
                }
                $offer->metas()->sync( $meta_sync );

                DB::table('huboffers')->insert([
                    'offer_id' => $offer->id,
                    'sort_flag' => 100 - 10 * $offer->id,
                    'visible' => 1,
                    'created_at' => now()
                    ]);

                DB::table('timestamps')->insert([
                    'offer_id' => $offer->id,
                    'executed_from' => now(),
                    'listed_from' => now(),
                    'active' => 1,
                    'created_at' => now()
                    ]);
            }
        } else {
            echo "Offers table not empty. Skipping...\n";
        }
    }
}
