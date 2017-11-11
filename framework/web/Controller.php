<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:11
 */
namespace framework\web;

use framework\base\Container;

abstract class Controller extends \framework\base\Controller
{
    protected function rule()
    {
        return array();
    }

    /**
     * desc 获取请求参数中的get参数
     * @param $key
     * @param null $default
     * @return null
     */
    protected function get($key = null, $default = null)
    {
        return $this->getComponent('request')->get($key,$default);
    }

    /**desc  获取请求参数中的post参数
     * @param $key
     * @param null $default
     */
    protected function post($key = null, $default = null)
    {
        return $this->getComponent('request')->post($key,$default);
    }

    /**
     * 获取请求参数中的所有参数 包括get和post
     */
    protected function requestParams()
    {
        return $this->getComponent('request')->request();
    }

    protected function isPost() : bool
    {
        if ($this->getComponent('request')->getMethod() === 'post')
        {
            return true;
        }
        return false;
    }

    protected function isAjax() : bool
    {
        $server = $this->getComponent('url')->getServer();
        $result = isset($server['HTTP_X_REQUESTED_WITH']) && $server['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';
        unset($server);
        return $result;
    }

    protected function ajax($data)
    {
        $urlComponent = $this->getComponent('response');
        $urlComponent->noCache();
        $urlComponent->contentType('json');
        unset($urlComponent);
        return $data;
    }

    protected function redirect($url)
    {
        $response = $this->getComponent('response');
        $response->addHeader('Location', $url);
        $response->setCode(302);
        unset($response);
        return '';
    }

    protected function assign($key, $value = null)
    {
        $this->getComponent('view')->assign($key, $value);
    }

    protected function display($path = '')
    {
        return $this->getComponent('view')->display($path);
    }

    protected function model($name)
    {
        $componentModel = md5(APP_NAME.'application/controller/'.$name);
        Container::getInstance()->addComponent($componentModel,
            'application\\model\\'. $name);

        return $this->getComponent($componentModel);
    }

    protected function createUrl($url)
    {
        $urlModule = $this->getComponent('url');
        $type = $urlModule->getType();
        $tmpUrl = $urlModule->getHost() . $urlModule->getUrl() . '?';
        if ($type === '?')
        {
            if(is_array($url))
            {
                foreach ($url as $key=>$item)
                {
                    $tmpUrl .= $key . '=' . $item . '&';
                }
                $tmpUrl = trim($tmpUrl, '&');
            }
            else
            {
                $tmpUrl .= $url;
            }
        }
        else
        {
            $tmpUrl .= $url;
        }

        unset($urlModule, $url);
        return $tmpUrl;
    }

    protected function validate() : bool
    {
        $rule = $this->rule();
        if (empty($rule[$this->_action]))
        {
            unset($rule);
            return true;
        }
        $data = array('get' => $this->get(),'post' => $this->post());
        $result = $this->getComponent('validate')->run($data, $rule[$this->_action]);
        unset($rule, $data);
        return $result;
    }

    protected function getSession()
    {
        return $this->getComponent('session');
    }

    protected function getCache()
    {
        return $this->getComponent('cache');
    }

    protected function getPage()
    {
        return $this->getComponent('page');
    }

    protected function getRedis()
    {
        return $this->getComponent('redis');
    }
}