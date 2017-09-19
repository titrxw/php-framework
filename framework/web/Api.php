<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:11
 */
namespace framework\web;

abstract class Api extends \framework\base\Controller
{
    protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $urlComponent = $this->getComponent('response');
        $urlComponent->noCache();
        $urlComponent->contentType('json');
        unset($urlComponent);
    }

    protected function getSession()
    {
        return $this->getComponent('session');
    }

    protected function getCache()
    {
        return $this->getComponent('cache');
    }
}