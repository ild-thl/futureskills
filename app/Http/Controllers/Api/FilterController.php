<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Institution;
use App\Models\Competence;
use App\Models\Language;
use App\Models\Offertype;
use Illuminate\Support\Facades\Cache;

class FilterController extends Controller
{
    /**
     * Return Tags for filter
     *
     * @return \Illuminate\Http\Response
     */
    public function getTags(  ) {

        if ( Cache::has('filter_tags') ) {
            return response()->json( json_decode( Cache::get('filter_tags') ), 200 );
        } 

        $institutions = array();
        foreach( Institution::all() as $institution ) {
            $institutions[] = array(
                "id" => $institution->id,
                "identifier" => $institution->title,
                "description" => array(
                    "de" => $institution->title
                ),
            );
        }

        $languages = array();
        foreach( Language::all() as $language ) {
            $languages[] = array(
                "id" => $language->id,
                "identifier" => $language->identifier,
                "description" => array(
                    "de" => $language->description
                ),
            );
        }

        $competences = array();
        foreach( Competence::all() as $competence ) {
            $competences[] = array(
                "id" => $competence->id,
                "identifier" => $competence->identifier,
                "description" => array(
                    "de" => $competence->description
                ),
            );
        }
        $formats = array();
        foreach( Offertype::all() as $format ) {
            $formats[] = array(
                "id" => $format->id,
                "identifier" => $format->identifier,
                "description" => array(
                    "de" => $format->description
                ),
            );
        }

        $output = array(
            "filter" => array(
                array(
                    "tag" => "institutions",
                    "list" => $institutions
                ),
                array(
                    "tag" => "languages",
                    "list" => $languages
                ),
                array(
                    "tag" => "competences",
                    "list" => $competences
                ),
                array(
                    "tag" => "formats",
                    "list" => $formats
                )
            )
        );

        Cache::put('filter_tags', json_encode($output));
        
        return response()->json($output, 200);
    }
}
