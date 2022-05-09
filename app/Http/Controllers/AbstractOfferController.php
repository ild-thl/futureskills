<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offertype;
use App\Models\Language;
use App\Models\Competence;
use App\Models\Meta;
use App\Models\Huboffer;
use App\Models\Timestamp;
use App\Models\Offer;

abstract class AbstractOfferController extends Controller
{
    /**
     * Support for using API readable parameterd
     * 'type' instead of 'offertype_id'
     * 'language' instead of 'language_id'
     *
     * @param  Array $validatedData
     * @return  Array $validatedData
     */
     function validateRedundantInput( Array $validatedData ) {

        if ( !key_exists ( "offertype_id", $validatedData ) && !empty ( $validatedData["type"] ) ) {
            $offertype = Offertype::where([ "identifier" => $validatedData["type"] ])->first();
            if ( is_object( $offertype ) ) {
                $validatedData["offertype_id"] = $offertype->id;
            }
        }

        if ( !key_exists ( "language_id", $validatedData ) && !empty ( $validatedData["language"] ) ) {
            $language = Language::where([ "identifier" => $validatedData["language"] ])->first();
            if ( is_object( $language ) ) {
                $validatedData["language_id"] = $language->id;
            }
        }
        return $validatedData;

    }


    /**
     * Save an offer's data in other tables
     *
     * @param  \App\Models\Offer $offer
     * @param  Array $validatedData
     */
     function saveRelatedData( Offer $offer, Array $validatedData ) {

        # Sync pivot tables
        $competences = Competence::all();
        $competences_sync = array();

        foreach ( $competences as $c ) {

            if ( \key_exists( "competence_".$c->identifier, $validatedData ) && $validatedData["competence_".$c->identifier]){
                    $competences_sync[] = $c->id;
                    $offer->competences()->sync($competences_sync, false);

            }elseif(\key_exists( "competence_".$c->identifier, $validatedData ) && !$validatedData["competence_".$c->identifier]){
                $offer->competences()->detach($c->id);
                $offer->competences()->sync($competences_sync, false);
            }
        }

        $metas = Meta::all();
        $meta_sync = array();

        foreach ( $metas as $m ) {
            if ( \key_exists( $m->description, $validatedData )  ) {

                if(empty ( $validatedData[$m->description] )){
                    $offer->metas()->detach($m->id);
                }else{
                    $meta_sync[ $m->id ] = [ "value" => $validatedData[$m->description] ];
                }
            }
        }
        $offer->metas()->sync($meta_sync,false);

        # Fill other related tables
        $hubOffer = Huboffer::where([ "offer_id" => $offer->id ])->first();
        if ( is_object( $hubOffer ) ) {
            $hubOffer->fill($validatedData);
            $hubOffer->save();
        }  else {
            $validatedData["offer_id"] = $offer->id;
            $hubOffer = Huboffer::create($validatedData);
        }

        $timestamp = Timestamp::where([ "offer_id" => $offer->id ])->first();
        if ( is_object( $timestamp ) ) {
            $timestamp->fill($validatedData);
            $timestamp->save();
        }  else {
            $validatedData["offer_id"] = $offer->id;
            $timestamp = Timestamp::create($validatedData);
        }

        if ( array_key_exists( "relatedOffers", $validatedData ) ) {
            $relations = $validatedData["relatedOffers"];
            $relations_sync = array();
            foreach ( $relations as $relation ) {
                # empty array [ 0 => null ]
                if ( $relation === null && count( $relations ) == 1 ) {
                    $offer->originalRelations()->detach();
                } else {
                    $relations_sync[] = intval($relation);
                }
            }
            $offer->originalRelations()->sync($relations_sync);
        }
    }
}
