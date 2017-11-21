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
//        return array(
//            'testAction' => array(
//                'id|get|请求编号'=>'require|integer',
//                'mobile|get|电话号码' => 'regex|/^1[34578]\d{9}$/'
//            ),
//            'testAction' => array(
//                'id|post|请求编号'=>'url',
//                'name|post|请求姓名' => 'require',
//            )
//        );
    }

    protected function init()
    {
        parent::init();
        $this->_userM = $this->model('User');
    }

    public function indexAction()
    {
//        var_dump($this->getComponent('aes')->encrypt(123));
        $_SESSION['test'] = 12;
        $this->redis->selectDb(1);
        $this->redis->selectRollBack();
        $this->redis->set('index', array(1,223,3,4,5));
//        var_dump($this->session);
        //var_dump($this->getComponent('Logger',1));
//        $this->assign(array(
//            'page' => $this->page->out(100,null,array('tr' => 'ty')),
//            'content' => '3rere'
//        ));
//        return $this->display('de/er');
//https://easywechat.org/zh-cn/docs/configuration.html
        $wechat = $this->getComponent('wechat', array(
            'app_id'  => 'your-app-id',         // AppID
            'secret'  => 'your-app-secret',     // AppSecret
            'token'   => 'your-token',          // Token
            'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！
        ));
//        $result = $this->getComponent('aes');
        return $this->redis->get('index');
    }

    public function testAction()
    {
        $log = $this->getComponent('Logger',1,2,3,4);
        return array(200, $this->_userM->getList());
    }

    public function imgAction()
    {
//        $this->getComponent('captcha')->getCode();
        return $this->getComponent('captcha')->send();
    }
}