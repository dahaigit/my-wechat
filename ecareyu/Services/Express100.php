<?php
namespace EcareYu\Services;

use GuzzleHttp\RequestOptions;

class Express100
{
    /**
     * 快递100物流类
     * 官方地址：https://www.kuaidi100.com/
     */

    /**
     * @var 身份KEY
     */
    public $id;

    /**
     * @var 身份
     */
    public $customer;

    /**
     * @var 参数数据
     */
    public $data;

    /**
     * 快递100接口基础URL
     * @var string
     */
    public $baseUrl = 'http://poll.kuaidi100.com/poll/query.do';

    /**
     * E快递100对象初始化
     * @param $config Array
     *  array(
     *       'id' 身份KEY
     *       'customer' 身份
     * )
     */
    public function __construct(array $config)
    {
        $this->id = $config['id'];
        $this->customer = $config['customer'];
    }

    /**
     * 根据快递单号获取物流信息
     * @param $num       快递单号
     * @param $com       物流公司代码
     * @author mhl
     * @date 2018-08-08 12:00:00
     */
    public function getExpress($num, $com)
    {
        $param = [
            "com" => $com,
            "num" => $num,
        ];
        $params = [
            'customer' => $this->customer,
            'param' => json_encode($param),
            'sign' => $this->getSign($param),
        ];
        // 获取物流信息
        return UtilService::guzzleData($this->baseUrl, $params, 'post',RequestOptions::FORM_PARAMS);
    }

    /**
     * 获取参数签名
     * @param $params
     * @author luwei
     * @date 2018-08-08 12:00:00
     */
    private function getSign($param)
    {
        return strtoupper(md5(json_encode($param) . $this->id . $this->customer));
    }
}
