<?php

/*
 * This file is part of the hedeqiang/alchemypay.
 *
 * (c) hedeqiang <laravel_code@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hedeqiang\AlchemyPay\Traits;

trait Utils
{
    /*
    * 去除空值的元素
    */
    protected function clearBlank($arr)
    {
        return array_filter($arr, function ($var) {
            return '' != $var;
        });
    }

    /**
     * 按照 键名 对关联数组进行升序排序：.
     *
     * @param $params
     * @return array
     */
    protected function getSortParams($params): array
    {
        $data = [];
        $params = $this->clearBlank($params);
        foreach ($params as $k => $var) {
            if (is_scalar($var) && '' !== $var) {
                $data[$k] = $var;
            } elseif (is_object($var)) {
                $data[$k] = array_filter((array)$var);
            } elseif (is_array($var)) {
                $data[$k] = array_filter($var);
            }
            if (empty($data[$k])) {
                unset($data[$k]);
            }
        }

        ksort($data);

        return $data;
    }

    public function getSignString(array $params,string $privateKey )
    {
        $signStr = '';
        foreach ($params as $key => $value) {
            $signStr .= sprintf('%s=%s&', $key, $value);
        }
        $signStr .= 'key=' . $privateKey;

        return strtoupper(md5($signStr));
    }

}
