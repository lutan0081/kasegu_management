<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Log;

use Storage;

// 暗号化
use Illuminate\Support\Facades\Crypt;

// データ縮小
use InterventionImage;

// app.phpでclassを設定し、コマンドプロントで"composer dump-autoload"実行後use Commonのみで使用できる
use Common;

class TestController extends Controller
{     
    /**
     * 表示
     *
     * @param Request $request('list','list_types','list_uses','list_relation','list_insurance','list_img','applicationFlag')
     */
    public function testInit(Request $request)
    {   
        $file_path = "";
        return view('test.test', compact('file_path'));
    }

    /**
     * ファイルアップロード
     *
     * @param Request $request
     * @return void
     */
    public function testImgEntry(Request $request){
        // アップロードされたファイルを取得
        $file_img = $request->file('file_img');
        Log::debug($file_img);

        // ファイル名(任意) + ファイルの拡張子
        $file_name = time() .'.' .$file_img->getClientOriginalExtension();
        Log::debug($file_name);

        // InterventionImage::make($file_img)->resize(512, 256)->save(storage_path('app/public/img/' .$file_name));
        InterventionImage::make($file_img)->resize(800, null,
            function ($constraint) {
                $constraint->aspectRatio();
            })->save(storage_path('app/public/img/' .$file_name));

        Log::debug('ファイル名:' .$file_name);
        $file_path = 'img/' .$file_name; //public_path('img/' .$file_name);

        // // public不要
        // $file_path = str_replace('public/', '', $tmp_file_path);
        Log::debug('ファイルパス:' .$file_path);

        // 出力値を設定
        $response = [];
        $response['file_path'] = $file_path;

        return response()->json($response);
    }
}
