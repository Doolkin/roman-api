<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\IntegerConversionInterface;
use Illuminate\Support\Facades\Validator;

class IntegerConversionsController extends Controller
{

    protected $integerConversion;

    function __construct(IntegerConversionInterface $integerConversion)
    {
        $this->integerConversion = $integerConversion;
    }

    // Displaying (20 by default) most recent conversions, link APP_Location/api
    public function index()
      {
        return response()->json($this->integerConversion->findRecent());    
      }

    // Displaying Top (10 by default) Converted integers, link APP_Location/api/top
    public function top()
      {
        return response()->json($this->integerConversion->findTop());        
      }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $rules = ['roman_integer' => 'required|integer|between:1,3999'];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 200);
        }
        
        return $this->integerConversion->saveNew($request->input('roman_integer'));
    }
    public function toRomanNumerals($integer) {
    }
}
