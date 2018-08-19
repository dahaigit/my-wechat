<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Wechat\Wechat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use Wechat;

    /**
     * 用户登陆
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function login(Request $request)
    {
        $code = $request->code;
        if (!$code)
        {
            dd('没有code');
        }
        $app = $this->getApp();
        // 2、通过code获取token
        $accessToken = $app->oauth->getAccessToken($code);
        dd($accessToken);

        // 3、获取用户信息

    }

    /**
     * 1、客户端发起授权
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function clientStartOAuth(Request $request)
    {
        $app = $this->getApp();
        $response = $app->oauth->scopes(['snsapi_userinfo'])->stateless()
            ->redirect();
        return $response;
    }
}
