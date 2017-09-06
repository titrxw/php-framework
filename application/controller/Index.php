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

    protected function rule()
    {
        return array(
            'indexAction' => array(
                'id|get|请求编号'=>'require|integer',
                'mobile|get|电话号码' => 'regex|/^1[34578]\d{9}$/'
            ),
            'testAction' => array(
                'id|post|请求编号'=>'url',
                'name|post|请求姓名' => 'require',
            )
        );
    }

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