<?php
namespace framework\base;

abstract class Controller extends Component
{
    protected $_controller;
    protected $_action;
    protected $_view;

    public function beforeAction()
    {
        return true;
    }

    public function afterAction($data = '')
    {
        return $data;
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
     * desc component 快捷获取方式
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $func = 'get'.ucfirst($name);
        if (method_exists($this, $func))
        {
            $this->$name = $this->$func();
//            保证只会调用一次func
            return $this->$name;
        }
        return null;
    }
}