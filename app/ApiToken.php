<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{

    /**
     * Key types
     */
    const TYPE_BASIC = 0;

    /**
     *
     */
    public static function typeFromSourceString( $string ) {
        return ApiToken::TYPE_BASIC;
    }

    /**
     *
     */
    public static function stringFromType( $type ) {
        return 0;
    }

    /**
     *
     */
    public function getTypeName() {
        return ApiToken::stringFromType($this->type);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo("\App\User");
    }

    /**
     * @param $query
     */
    public function scopeIntegrationTokens($query)
    {
        $query->whereIn("type", static::$integration_types);
    }

    /**
     * Generates SSH token
     */
    public function generate(){
        $this->token = Str::random(80);
    }

    /**
     *
     */
    public function hasSSHKey(){
        return $this->public_ssh_key;
    }

    /**
     * @param $public_ssh
     */
    public function setSSHKey( $public_ssh ){

        if($this->public_ssh_key == $public_ssh)
            return;

        $this->public_ssh_key = trim($public_ssh);
        $this->save();


    }

}














