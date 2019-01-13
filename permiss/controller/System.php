<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2019/1/10
 * Time: 21:35
 */
namespace permiss\controller;
use permiss\lib\User;

class System extends User
{
    /**
     * @method get
     */
    public function menuApi()
    {
        return [200, $this->getMenu()];
    }
}