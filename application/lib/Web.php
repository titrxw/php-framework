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
        //在这里进行数据校验  数据校验模块正在开发
        $this->assign('publicPath','public/assets/application/');
        return true;
    }

    public function afterAction()
    {
        $this->getComponent('session')->destroy();
        return true;
    }

}
