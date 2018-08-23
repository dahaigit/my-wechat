<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Auththenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Auththenticatable
{
    use HasApiTokens, Notifiable;

    const WECHAT_PASSPORT_SPEC_STR = "Who's your daddy!";
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

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

    /**
     * 密码加密
     *
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = \Hash::make($password);
    }

    /**
     * 修改登录的字段，laravel默认是email
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function findForPassport($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * 获取微信特殊字段
     *
     * @return string
     */
    public static function getWechatSpecStr()
    {
        return md5(self::WECHAT_PASSPORT_SPEC_STR);
    }

    /**
     * Passport 特殊密码验证
     *
     * @param null $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password = null)
    {
        return $password === self::getWechatSpecStr() ? true : \Hash::check($password, $this->password);
    }

    /**
     * 用户的任务
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public function tasks()
    {
        return $this->hasMany(Task::class, 'user_id', 'id');
    }
}
