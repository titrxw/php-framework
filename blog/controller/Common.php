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

    protected function rule()
    {
        return array(
            'loginApi' => array(
               'mobile|post|账号格式错误'=>'regex|/^1[34578]\d{9}$/',
               'password|post|密码格式错误' => 'require'
            ),
            'registerApi' => array(
                'mobile|post|账号格式错误'=>'regex|/^1[34578]\d{9}$/',
                'password|post|密码格式错误' => 'require',
                'sure_password|post|确认密码格式错误' => 'require'
            ),
            'sendMsgApi' => array(
                'mobile|get|手机号格式错误'=>'regex|/^1[34578]\d{9}$/'
            )
        );
    }

    protected function afterInit()
    {
        $this->_userM = $this->model('User');
    }

    public function testApi()
    {
        var_dump(getFiles(APP_ROOT . '/blog'));
        $this->cookie->set('rwar', 'dsfsdf');
        var_dump(uniqueId());
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

    public function sendMsgApi()
    {
        $this->tokenBucket->validate('mobile', ['mobile' => $this->request->get('mobile')]);

        return [200, '发送成功'];
    }

     public function imgApi()
     {
         return $this->captcha->send();
     }

//     public function downloadAction()
//     {
//         return $this->sendFile(APP_ROOT. '/public/assets/' . APP_NAME. '/images/1457781452.jpg', 'jpg');
//     }
}