<?php
namespace framework\components\dispatcher;
use framework\base\Component;
use framework\base\Container;

class Dispatcher extends Component
{
    protected $_system;

    public function run($args = [])
    {
        $this->_system = \getModule();
        $args['controller'] = \ucfirst($args['controller']);

        $controllerConf = Container::getInstance()->getComponentConf(\getModule(), 'controller');

        $controllerName = ($controllerConf['prefix'] ?? '') . $args['controller'] . ($controllerConf['suffix'] ?? '');
        if (!\file_exists(APP_ROOT.$this->_system.'/controller/'.$controllerName.'.php'))
        {
            $this->triggerThrowable(new \Exception(APP_ROOT.$this->_system.'/controller/'.$controllerName.'.php not exists', 404));
        }
        $controllerHashName = \md5($this->_system.'/controller/'.$controllerName);

        $actionConf = Container::getInstance()->getComponentConf(\getModule(), 'action');
        $actionName = ($actionConf['prefix'] ?? '') . $args['action'] . ($actionConf['suffix'] ?? '');

        Container::getInstance()->addComponent($this->_system, $controllerHashName,
            $this->_system.'\\controller\\'. $controllerName,['controller' => $controllerConf, 'action' => $actionConf]);

        $controllerInstance = $this->getComponent(\getModule(), $controllerHashName);
        if (!\method_exists($controllerInstance, $actionName))
        {
            unset($controllerInstance, $args);
            $this->triggerThrowable(new \Exception('action ' . $actionName . ' not found'));
        }

        // 请求限制
        if ($this->getValueFromConf('route', false)) {
            $methods = Container::getInstance()->getComponent(SYSTEM_APP_NAME, 'doc')->parse($controllerInstance, $actionName)->getTags('method');
            if($methods) {
                $upMethod = \strtoupper($args['method']);
                $lowMethod = \strtolower($args['method']);
                if (!\in_array($lowMethod,$methods) && !\in_array($upMethod,$methods)) {
                    $this->triggerThrowable(new \Exception('action ' . $actionName . ' not found', 404));
                }
            }
        }
        
        $controllerInstance->setController($controllerName);
        $controllerInstance->setAction($actionName);

        $result = $controllerInstance->before();
        if ($result === true)
        {
            // 这里尝试把数据放到action的参数中   只放get的数据
            $_params = [];
            $params = new \ReflectionMethod($controllerInstance, $actionName);
            $params = $params->getParameters();
            $request = $this->getComponent(SYSTEM_APP_NAME, 'request');
            foreach($params as $item) {
                $val = $request->get($item->name);
                if (!$val && $item->isDefaultValueAvailable()) {
                    $val = $item->getDefaultValue ();
                }
                $_params[] = $val;
            }
            if ($_params) {
                $result = $controllerInstance->$actionName(...$_params);
            } else {
                $result = $controllerInstance->$actionName();
            }
        }

        $result = $controllerInstance->after($result);
        unset($controllerInstance, $args);
        return $result;
    }
}
