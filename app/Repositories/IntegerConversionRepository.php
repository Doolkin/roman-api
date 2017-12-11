<?php

namespace App\Repositories;

use App\IntegerConversion;
use App\IntegerConversionInterface;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;

use \App\Transformers\IntegerConversionTransformer;


class IntegerConversionRepository implements IntegerConversionInterface
{
	protected $integerConversion;
	private $fractal;
    private $IntegerConversionTransformer;


	public function __construct( IntegerConversion $integerConversion, Manager $fractal, IntegerConversionTransformer $integerConversionTransformer )
	{
		$this->integerConversion = $integerConversion;
		$this->fractal = $fractal;
		$this->integerConversionTransformer = $integerConversionTransformer;
	}

	public function findRecent( $limitNumber = 20 ){
		//Obtaining data
		$resource =  $this->integerConversion->orderBy('created_at', 'DESC')->limit($limitNumber)->get();
		
		//Creating "Collection" since there can be more than 1 result
		$resource = new Collection($resource, $this->integerConversionTransformer);
        $resource = $this->fractal->createData($resource); // Transform data
        $resource = $resource->toArray();
        
        return $resource;
	}

	public function findTop( $topNumber = 10 ){
		//Obtaining data
		$resource = $this->integerConversion->orderBy('times_converted', 'DESC')->limit($topNumber)->get();
       
        //Creating "Collection" since there can be more than 1 result
        $resource = new Collection($resource, $this->integerConversionTransformer);
        $resource = $this->fractal->createData($resource); // Transform data
        $resource = $resource->toArray();
       
        return $resource;
	}

	public function saveNew( $theInteger ){
		
		// Just to be sure that the value is numeric
		if ( is_numeric($theInteger) == true && ($theInteger >= 1 && $theInteger <=3999 ) ) {

            // Converting the given integer to roman format using model method (method is in the model)
            $romanFormatInteger = $this->toRomanNumerals( $theInteger );

            // Creating new record if roman numeral value is not existing or incrementing converted times if it is existing
            $resource = $this->integerConversion->firstOrNew( array('roman_integer' => $romanFormatInteger) );
           
            // Incrementing total times that the integer was converted if it exist
            $resource->times_converted += 1;
            $resource->save();

            /**
             * Create a resource collection transformer, in this case ITEM is used instead of COLLECTION, 
             * since its a single item when posting
             */
            $resource = new Item($resource, $this->integerConversionTransformer);
            // Transform data with correct Fractal transformer 
            $resource = $this->fractal->createData($resource); 

            // Success response
            return response()->json( $resource->toArray(), 201 );

        } else {

            // Post failed
            return response()->json( ['success' => false], 401 );
        }
	}

	public function toRomanNumerals ( $theInteger ){

		// Making sure it will be integer, just in case
		$theInteger = intval($theInteger);

		// Array containing Roman numerals, so we can loop through them
		$map = array(
			'M' => 1000, 
			'CM' => 900, 
			'D' => 500, 
			'CD' => 400, 
			'C' => 100, 
			'XC' => 90, 
			'L' => 50, 
			'XL' => 40, 
			'X' => 10, 
			'IX' => 9, 
			'V' => 5, 
			'IV' => 4, 
			'I' => 1
		);

		// Initializing the return value
	    $returnValue = '';

	    // Looking up into Roman numerals array $map , and pairing up arabic with roman 
	    while ($theInteger > 0) {
	        foreach ($map as $roman => $int) {
	            if($theInteger >= $int) {
	                $theInteger -= $int;
	                $returnValue .= $roman;
	                break;
	            }
	        }
	    }

    return $returnValue;
	}
	

}