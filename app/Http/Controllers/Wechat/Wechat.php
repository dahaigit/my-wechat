<?php
namespace App\Http\Controllers\Wechat;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 8/17/2018
 * Time: 2:55 PM
 */
use EasyWeChat\Factory;

trait Wechat
{
    /**
     * 获取微信对象
     */
    public function getApp()
    {
        $config = config('wechat');
        $app = Factory::officialAccount($config);
        return $app;
    }
}