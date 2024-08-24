<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;

class CustomController extends BaseController
{
    protected $loginPath = '/login';
    protected $redirectPath = '/';
    protected $redirectTo = '/';
    public $data = [];

    public function __construct()
    {
        Parent::__construct();
    }

}
