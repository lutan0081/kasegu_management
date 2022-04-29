<?php

namespace App\Http\Controllers\Front\SiteUse;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;

use App\Config;

use Common;

class FrontSiteUseController extends Controller
{   
    /**
     * サイト利用規約(表示)
     */
    public function frontSiteUseInit()
    {     
        return view('front.frontSiteUse', []);
    }
}