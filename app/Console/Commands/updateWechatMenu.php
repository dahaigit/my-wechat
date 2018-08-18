<?php

namespace App\Console\Commands;

use App\Http\Controllers\Wechat\Wechat;
use Illuminate\Console\Command;

class updateWechatMenu extends Command
{
    use Wechat;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:wechat-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '手动更新微信菜单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $buttons = [
            [
                "type" => "view",
                "name" => "首页",
                "url"  => "http://wechat.subprice.cn",
            ],
            [
                "name"       => "菜单",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "授权",
                        "url"  => "http://wechat.subprice.cn/api/login"
                    ],
                    [
                        "type" => "view",
                        "name" => "视频",
                        "url"  => "http://v.qq.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "赞一下我们",
                        "key" => "V1001_GOOD"
                    ],
                ],
            ],
        ];
        $app = $this->getApp();
        $app->menu->create($buttons);
    }
}
