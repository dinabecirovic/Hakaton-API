<?php

namespace App\Http\Controllers\Api;



class Controller extends \App\Http\Controllers\Controller
{

    /**
     * LoginController constructor.
     */
    public function __construct() {

        $this->middleware('auth:api')
            ->except('login');

        $this->middleware('auth.integrations-check');

    }

}
