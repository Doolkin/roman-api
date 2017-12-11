<?php
namespace App\Transformers;

use \App\IntegerConversion;
use League\Fractal\TransformerAbstract;

class IntegerConversionTransformer extends TransformerAbstract
{

    public function transform(IntegerConversion $integerConversion)
    {
        return [
            'id' => $integerConversion->id,
            'romanInteger' => $integerConversion->roman_integer,
            'createdAt' => $integerConversion->created_at->toDateTimeString(),
            'updatedAt' => $integerConversion->updated_at->toDateTimeString(),
            'timesConverted' => $integerConversion->times_converted
        ];
    }
}