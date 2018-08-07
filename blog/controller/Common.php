<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace blog\controller;
use blog\lib\Web;

class Common extends Web
{
    private $_userM;
    
    protected function afterInit()
    {
        $this->_userM = $this->model('User');
    }

    /**
     * @method get
     * 
     * @params string  $name 不能为空
     * @rule mobile|post|账号格式错误 regex|/^1[34578]\d{9}$/  
     * @rule password|post|密码格式错误 require
     * @rule sure_password|post|确认密码格式错误 require
     */
    public function testApi()
    {
        $this->cookie->set('rwar', 'dsfsdf');
        // return $this->_userM->test();
        // var_dump(uniqueId());
//        $this->addTask('msgTask', 'sendMsg', array('mobile' => '1212121212'));
    }

    public function loginApi ()
    {
        var_dump(1);
        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');

        $result = $this->_userM->login($mobile, $password);
        if ($result) {
            return [200, $result];
        }

        return [501, '登录失败'];
    }

    public function registerApi()
    {
        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');
        $sure_password = $this->request->post('sure_password');
        if ($password !== $sure_password) {
            return [501, '确认密码错误'];
        }

        $result = $this->_userM->register($mobile, $password);
        if ($result) {
            return [200, $result];
        }

        return [501, '注册失败'];
    }

    /**
     * @method get
     * 
     * @rule mobile|get|手机号格式错误 regex|/^1[34578]\d{9}$/
     */
    public function sendMsgApi()
    {
        $this->tokenBucket->validate('mobile', ['mobile' => $this->request->get('mobile')]);

        return [200, '发送成功'];
    }

    public function imgApi()
    {
        return $this->captcha->send();
    }

    public function downloadApi()
    {
        return $this->sendFile(APP_ROOT. '/public/assets/' . \getModule(). '/images/1457781452.jpg', 'csv');
    }
}