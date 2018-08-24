<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Wechat\Wechat;
use App\Models\User;
use App\Models\UserWechat;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    use Wechat;

    /**
     * 用户登陆
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function login(Request $request)
    {
        if ($request->code && $request->code != 1) {
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
                    $user = $userWechat;
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
                throw $exception;
            }
        } else {
            // 模拟登录
            $user = User::find(1);
        }
        // 5、使用passport登录
        $token =  $this->passportLogin($user);
        return $this->response('登陆成功', $token);
    }

    /**
     * passport登录
     * @param $user
     * @return mixed
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function passportLogin($user)
    {
        $issueTokenUrl = url('oauth/token');
        try {
            $http = new \GuzzleHttp\Client();
            $response = $http->post($issueTokenUrl, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('API_CLIENT_ID'),
                    'client_secret' => env('API_CLIENT_SECRET'),
                    'username' => $user->id,
                    'password' => User::getWechatSpecStr(),
                    'scope' => '',
                ],
            ]);
            return json_decode($response->getBody(), true);
        } catch (ClientException $clientException) {
            throw $clientException;
        }
    }

    /**
     * 1、客户端发起授权
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function clientStartOAuth(Request $request)
    {
        /*https://open.weixin.qq.com/connect/oauth2/authorize?
        appid=wx4aebe20c12f506a3&
        redirect_uri=http%3A%2F%2Fwechat.subprice.cn%2Fapi%2Flogin&
        response_type=code&
        scope=snsapi_userinfo&
        state=bce3a9ef3cf953f5948d1147ca328514&
        connect_redirect=1#wechat_redirect*/
        $app = $this->getApp();
        $response = $app->oauth->scopes(['snsapi_userinfo'])->stateless()
            ->redirect();
        return $response;
    }
}
