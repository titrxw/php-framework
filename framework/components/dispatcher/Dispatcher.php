<?php
namespace framework\components\dispatcher;
use framework\base\Component;
use framework\base\Container;

class Dispatcher extends Component
{
    protected $_system;
    protected $_controller;
    protected $_action;

    public function run($args = [])
    {
        $this->_system = \getModule();
        $controllerName = $this->getValueFromConf('controller.prefix') . $args['controller'] . $this->getValueFromConf('controller.suffix');
        $controllerName = \ucfirst($controllerName);
        if (!\file_exists(APP_ROOT.$this->_system.'/controller/'.$controllerName.'.php'))
        {
            $this->triggerThrowable(new \Exception(APP_ROOT.$this->_system.'/controller/'.$controllerName.'.php not exists', 404));
        }

        $controllerHashName = \md5($this->_system.'/controller/'.$controllerName);

        Container::getInstance()->addComponent($this->_system, $controllerHashName,
            $this->_system.'\\controller\\'. $controllerName, Container::getInstance()->getComponentConf(\getModule(), 'controller'));

        $actionName = $this->getValueFromConf('action.prefix') . $args['action'] . $this->getValueFromConf('action.suffix');
        $controllerInstance = $this->getComponent(\getModule(), $controllerHashName);
        if (!\method_exists($controllerInstance, $actionName))
        {
            unset($controllerInstance, $args);
            $this->triggerThrowable(new \Exception('action ' . $actionName . ' not found'));
        }
        $methods = Container::getInstance()->getComponent(SYSTEM_APP_NAME, 'doc')->parse($controllerInstance, $actionName)->getTags('method');
        if($methods && \strtoupper($methods[0]) != $args['method']) {
            $this->triggerThrowable(new \Exception('action ' . $actionName . ' not found'));
        }
        $controllerInstance->setController($controllerName);
        $controllerInstance->setAction($actionName);
        $this->_controller = $controllerName;
        $this->_action = $actionName;


        $result = $controllerInstance->before();
        if ($result !== true)
        {
            unset($controllerInstance, $args);
            return $result;
        }

        $result = $controllerInstance->$actionName();

        $result = $controllerInstance->after($result);
        unset($controllerInstance, $args);
        return $result;
    }

    public function getController ()
    {
        return $this->_controller;
    }

    public function getAction ()
    {
        return $this->_action;
    }
}