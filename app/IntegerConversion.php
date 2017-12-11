<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegerConversion extends Model
{
	protected $fillable = ['roman_integer'];

	/**
	 * Leaved that method here in the model just 
	 * for the unit testing (easier way =) ) 
	 * In this case, code will work even if the code here is removed, 
	 * since it is still in the repo
	 */
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