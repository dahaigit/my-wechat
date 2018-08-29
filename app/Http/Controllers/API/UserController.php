<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;


class UserController extends ApiController
{
    public function info(Request $request)
    {
        $user = $request->user();
        $data = [
            'user_id' => $user->id,
            'nickname' => $user->wechat->nickname,
            'headimgurl' => $user->wechat->headimgurl,
        ];
        return $this->response('请求成功', $data);
    }
}
