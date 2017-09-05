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
        $this->getComponent('session')->start();
        $this->assign('publicPath','public/assets/application/');
        return true;
    }

    public function afterAction()
    {
        $this->getComponent('session')->destroy();
        return true;
    }

}