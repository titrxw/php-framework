<?php
namespace framework\base;

abstract class Controller extends Component
{
    protected $_controller;
    protected $_action;
    protected $_view;

    protected function init()
    {
        $this->unInstall();
    }

    public function beforeAction()
    {
        return true;
    }

    public function afterAction()
    {
        return true;
    }

    public function setController($currentController)
    {
        $this->_controller = $currentController;
    }

    public function getController()
    {
        return $this->_controller;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
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
    protected function getRequestParams()
    {
        return $this->getComponent('request')->request();
    }

    protected function rediret($url)
    {
        $response = $this->getComponent('response');
        $response->addHeader('Location', $url);
        $response->setCode(302);
        unset($response);
        return '';
    }

    protected function isPost()
    {
        if ($this->getComponent('request')->getMethod() === 'post')
        {
            return true;
        }
        return false;
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

    protected function ajax($data)
    {
        $this->getComponent('response')->contentType('json');
        return json_encode($data);
    }

    protected function model($name)
    {
        try
        {
            $componentModel = md5(APP_NAME.'application/controller/'.$name);
            Container::getInstance()->addComponent($componentModel,
                'application\\model\\'. $name);
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage(), 404);
        }
        return $this->getComponent($componentModel);
    }
}