<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Offer;

class RelatedOfferRule implements Rule
{

    public $request;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct( $request )
    {
        $this->request = $request;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $relations = $value;

        # max length 6
        if ( count( $relations ) > 6 ) {
            return false;
        }

        # Update offer: $id is edited offer ID
        # Create offer: $id is "offer"
        $pathInfo = explode( "/", $this->request->getPathInfo() );
        $id = end($pathInfo);

        $relations_sync = array();
        foreach ( $relations as $relation ) {
            # empty array [ 0 => null ]
            if ( $relation === null && count( $relations ) == 1 ) {
                return true;
            }
            $tmpRelated = Offer::find($relation);

            # ID must exist; ID must not be same as edited offer; ID must be unique in array
            if ( $tmpRelated != null && $tmpRelated->id != intval($id) && !in_array( $tmpRelated->id, $relations_sync ) ) {
                $relations_sync[] = $tmpRelated->id;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The attribute "relatedOffers" must contain a string array with max. 6 valid Offer IDs: [1,2,3,4,5,6]';
    }
}
