<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:14
 */
namespace application\lib;
use framework\web\Controller;

abstract class Web extends Controller
{
    public function beforeAction()
    {
        $result  = $this->validate();
        if ($result !== true)
        {
            return $this->ajax(null,500,$result);
        }
        $this->getComponent('session')->start();
        return true;
    }

    public function afterAction()
    {
        $this->getComponent('session')->destroy();
        return true;
    }
}