<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Wechat\Wechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use Wechat;
    public $app;
    /**
     * 用户登陆
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function login(Request $request)
    {
        $this->app = $this->getApp();
        dd($this->app->oauth());
    }

    /**
     * 授权方法
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function auth()
    {

    }
}
