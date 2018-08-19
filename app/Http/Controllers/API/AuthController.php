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
        $app = $this->getApp();
        // 2、通过code获取token
        $accessToken = $app->oauth->getAccessToken($code);

        // 3、获取用户信息
        $oauthUser = $app->oauth->user($accessToken);

        // 4、存储已授权的用户
        $openId = $oauthUser->id;
        $getUser = $app->user->get($openId);
        // 查询用户是否存在，不存在添加，存在就更新
        $userWechat = UserWechat::where('open_id', $openId)->first();

        try {
            \DB::beginTransaction();
            if ($userWechat) {
                $userWechat->update([
                    'nickname' => $getUser['nickname'],
                    'sex' => $getUser['sex'],
                    'province' => $getUser['province'],
                    'city' => $getUser['city'],
                    'country' => $getUser['country'],
                    'headimgurl' => $getUser['headimgurl'],
                    'is_subscribe' => $getUser['subscribe'] ? 1 : 0,
                ]);
            } else {
                $user = User::create();
                UserWechat::create([
                    'user_id' => $user->id,
                    'open_id' => $openId,
                    'nickname' => $getUser['nickname'],
                    'sex' => $getUser['sex'],
                    'province' => $getUser['province'],
                    'city' => $getUser['city'],
                    'country' => $getUser['country'],
                    'headimgurl' => $getUser['headimgurl'],
                    'is_subscribe' => $getUser['subscribe'] ? 1 : 0,
                ]);
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
        }

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
