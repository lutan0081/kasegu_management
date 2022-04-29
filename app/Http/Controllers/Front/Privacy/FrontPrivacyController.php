<?php

namespace App\Http\Controllers\Front\Privacy;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Crypt;

use App\Config;

use Common;


class FrontPrivacyController extends Controller
{   
    /**
     * 個人情報保護方針(表示)
     */
    public function frontPrivacyInit()
    {     
        return view('front.frontPrivacy', []);
    }
}