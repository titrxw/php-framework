<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace application\controller;
use application\lib\Web;

class Index extends Web
{
    private $_userM;

    protected function init()
    {
        $this->_userM = $this->model('User');
        parent::init(); // 这里必须有  在运行结束后要回收
    }

    public function indexAction()
    {
//        var_dump($this->_userM->getList());
        $this->assign('content', '12121212fdgfd');
        return $this->display();
    }

    public function testAction()
    {
        return 1;
    }
}