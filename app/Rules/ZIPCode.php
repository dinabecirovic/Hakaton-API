<?php

namespace App\Rules;

use App\Country;
use App\WoocommerceClient;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;

class ZIPCode implements ValidationRule
{

    /**
     * @var string
     */
    public $country;

    /**
     * Create a new rule instance.
     */
    public function __construct( Country $country = null )
    {
        $this->country = $country;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        
        $client = new WoocommerceClient();

        try{

            $response = $client->validateZIP($this->country->code, $value);

            if($response->valid) 
                return; 

        }
        catch(Exception $e){
            //
        }
    
        $fail('The :attribute must be valid ZIP number for country: '. $this->country->name .'.');

    }
}
