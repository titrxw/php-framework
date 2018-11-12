<?php
namespace framework\base;

abstract class Controller extends Component
{
    protected $_dbHandle;
    protected $_controller;
    protected $_action;
    protected $_requestController;
    protected $_requestAction;
    protected $_view;


    protected function init()
    {
        $this->unInstall(true);
    }

    public function before()
    {
        return true;
    }

    public function after($data = '')
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

    public function getRequestController()
    {
        if ($this->_requestController) {
            return $this->_requestController;
        }
        $this->_requestController = $this->_controller;
        $this->_requestController = ltrim($this->_requestController, $this->getValueFromConf['controller.prefix']);
        $this->_requestController = rtrim($this->_requestController, $this->getValueFromConf['controller.suffix']);
        return $this->_requestController;
    }

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getRequestAction()
    {
        if ($this->_requestAction) {
            return $this->_requestAction;
        }
        $this->_requestAction = $this->_action;
        $this->_requestAction = ltrim($this->_requestAction, $this->getValueFromConf['action.prefix']);
        $this->_requestAction = rtrim($this->_requestAction, $this->getValueFromConf['action.suffix']);
        return $this->_requestAction;
    }

    public function db()
    {
        if (!$this->_dbHandle) {
            $this->_dbHandle = $this->getComponent(\getModule(), $this->getValueFromConf('db','meedo'));
        }
        return $this->_dbHandle;
    }

    /**
     * desc component 快捷获取方式
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        if (Container::getInstance()->hasComponent(\getModule(), $name)) {
            $this->$name = $this->getComponent(\getModule(), $name);
            return $this->$name;
        }
        if (Container::getInstance()->hasComponent(SYSTEM_APP_NAME, $name)) {
            $this->$name = $this->getComponent(SYSTEM_APP_NAME, $name);
            return $this->$name;
        }
        return null;
    }
}