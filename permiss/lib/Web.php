<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:14
 */
namespace permiss\lib;

use framework\web\Api;

abstract class Web extends Api
{
    public function before()
    {
        $this->header->add('Access-Control-Allow-Origin', '*');
        $result  = $this->validate();
        if ($result !== true)
        {
            return [500,$result];
        }
        return true;
    }

    public function after($data = array())
    {
        // 做log日志
        if (is_array($data))
        {
            $data['ret'] = $data[0] ?? 200;
            $data['data'] = $data[0] == 200 ? $data[1] ?? '' : '';
            $data['msg'] = $data[0] == 200 ? '' : $data[1] ?? '';
            unset($data[0], $data[1]);
        }
        return $data;
    }
}
