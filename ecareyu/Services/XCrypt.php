<?php
/**
 * 工具服务.
 * User: EcareYu
 * Date: 2017/9/27
 * Time: 16:52
 */

namespace EcareYu\Services;

class XCrypt
{

    /**
     * 简单对称加密
     * @param string $string [需要加密的字符串]
     * @param string $skey [加密的key]
     * @return [type]   [加密后]
     */
    public static function encode($string = '', $skey = 'rxds')
    {
        $strArr = str_split(base64_encode(json_encode($string)));
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key < $strCount && $strArr[$key].=$value;
        return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
    }

    /**
     * 简单对称解密
     * @param string $string [加密后的值]
     * @param string $skey [加密的key]
     * @return [type]   [加密前的字符串]
     */
    public static function decode($string = '', $skey = 'rxds', $isArr = false)
    {
        $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
        $strCount = count($strArr);
        foreach (str_split($skey) as $key => $value)
            $key <= $strCount && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
        return json_decode(base64_decode(join('', $strArr)), $isArr);
    }
}
