<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Wechat\Wechat;
use App\Models\User;
use App\Models\UserWechat;
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

        // 3、获取用户信息
        $oauthUser = $app->oauth->user($accessToken);

        // 4、存储已授权的用户
        $openId = $oauthUser->id;
        $getUser = $app->user->get($openId);
        $user = User::create();
        UserWechat::create([
            'user_id' => $user->id,
            'open_id' => $openId,
            'nickname' => $oauthUser->open_id,
            'sex' => $oauthUser->sex,
            'province' => $oauthUser->province,
            'city' => $oauthUser->city,
            'country' => $oauthUser->country,
            'headimgurl' => $oauthUser->headimgurl,
            'privilege' => $oauthUser->privilege,
            'unique_id' => $oauthUser->unique_id,
            'is_subscribe' => $getUser->is_subscribe ? 1 : 0,
        ]);
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
