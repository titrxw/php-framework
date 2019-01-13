<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace permiss\controller;
use permiss\lib\Web;

class Common extends Web
{
    private $_userM;
    
    protected function afterInit()
    {
        $this->_userM = $this->model('User');
    }

    /**
     * @method post
     * 
     * @rule name|post|昵称错误 require
     * @rule mobile|post|账号格式错误 regex|/^1[34578]\d{9}$/  
     * @rule password|post|密码格式错误 require
     * @rule sure_password|post|确认密码格式错误 require
     */
    public function registerApi()
    {
        $name = $this->request->post('name');
        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');
        $surePassword = $this->request->post('sure_password');
        if ($password !== $surePassword) {
            return [500, '确认密码错误'];
        }
        $user = $this->_userM->register($name, $mobile, $password);
        if ($user) {
            return [200, ['token' => $this->token->set($user)]];
        }
        return [500, '注册失败'];
    }

    /**
     * @method post
     * 
     * @rule mobile|post|账号格式错误 regex|/^1[34578]\d{9}$/  
     * @rule password|post|密码格式错误 require
     */
    public function loginApi ()
    {
        $mobile = $this->request->post('mobile');
        $password = $this->request->post('password');
        $user = $this->_userM->login($mobile, $password);
        $token = \token($user);
        $this->redis->set($token,$user);
        if ($user) {
            return [200,  ['token' => $token]];
        }
        return [501, '登录失败'];
    }
}