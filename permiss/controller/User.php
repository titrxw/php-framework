<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace permiss\controller;

class User extends \permiss\lib\User
{
    private $_userM;
      
    protected function afterInit()
    {
        $this->_userM = $this->model('User');
    }

    public function addApi()
    {

    }

    public function listApi()
    {

    }

    public function deleteApi()
    {

    }

    public function updateApi()
    {
      
    }
}