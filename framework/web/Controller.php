<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:11
 */
namespace framework\web;

abstract class Controller extends \framework\base\Controller
{
    protected function isAjax()
    {
        $server = $this->getComponent('url')->getServer();
        $result = isset($server['HTTP_X_REQUESTED_WITH']) && $server['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
        unset($server);
        return $result;
    }

    protected function assign($key, $value = null)
    {
        $this->getComponent('view')->assign($key, $value);
    }

    protected function display($path = '')
    {
        return $this->getComponent('view')->display($path);
    }
}