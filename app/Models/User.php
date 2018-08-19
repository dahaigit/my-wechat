<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = [];

    /**
     * 获取用户微信
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function wechat()
    {
        return $this->hasOne(UserWechat::class, 'id', 'user_id');
    }
}
