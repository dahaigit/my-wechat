<?php
/**
 * 工具服务.
 * User: EcareYu
 * Date: 2017/9/27
 * Time: 16:52
 */

namespace EcareYu\Services;

use App\Models\User;
use EcareYu\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class UtilService
{

    /**
     * 数据返回
     *
     * @param $message
     * @param int $code
     * @param array $meta 附加信息
     * @return \Illuminate\Http\JsonResponse
     */
    public static function response($message, $meta = [], $code = 0)
    {
        $out = [
            'msg' => $message,
            'code' => $code
        ];

        if ($meta) {
            // null转换成'' true|false转换成1|0
            array_walk_recursive($meta, function (&$item, $key) {
                if (is_null($item)) {
                    $item = '';
                } elseif (is_bool($item)) {
                    $item = (true === $item) ? 1 : 0;
                }
            });
            $out['meta'] = $meta;
        }

        return \Response::make($out);
    }

    /**
     * 错误文字
     *
     * @param $key errors语言包中key
     * @param array $values 变量
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    public static function error($key, $values = [])
    {
        $msg = __(sprintf('errors.%s', $key));
        if (count($values) > 0) {
            foreach ($values as $key => $value) {
                $msg = str_replace("#${key}#", $value, $msg);
            }
            return $msg;
        } else {
            return $msg;
        }
    }

    /**
     * 表单规则错误格式
     *
     * @param $code
     * @param array $values
     * @return array
     */
    public static function rulesErr($code, $values = [])
    {
        return implode('|', [self::error($code, $values), $code]);
    }

    /**
     * 抛异常专用工具
     *
     * @param $code
     * @param array $values
     * @throws ApiException
     */
    public static function thrownErr($code, $values = [])
    {
        throw new ApiException(self::error($code, $values), $code);
    }

    /**
     * 格式化金钱输出
     *
     * @param $price
     * @return string
     */
    public static function priceFormat($price)
    {
        return bcdiv(bcmul($price, 100, 0), 100, 2);
    }

    /**
     * 读取微信消息文件
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function readWechatMessageFile()
    {
        $filepath = storage_path('/framework/cache/wechat-message.json');

        if (!\File::exists($filepath)) {
            \Artisan::call('wechat:message-update');
        }

        $str = \File::get($filepath);

        return json_decode($str, true);
    }

    /**
     * 获取袋鼠有家分类名称
     * @return \Illuminate\Config\Repository|mixed
     * @author luwei
     * @date ${YEAR}-${MONTH}-${DAY} ${TIME}
     */
    public static function getBabysitterCategoryName()
    {
        return config('flybaby.babysitter_category_name');
    }

    /**
     * 第三方接口调用
     * @param $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public static function guzzleData($url, $params, $method = 'get', $key = RequestOptions::JSON)
    {
        $client = new Client();
        $res        = $client->$method($url, [$key => $params]);
        $res        = json_decode($res->getBody(), true);
        return $res;
    }
}
