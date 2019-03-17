<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 18-2-2
 * Time: 上午2:16
 */
namespace framework\base;


class Conf extends Component
{
    private $_config;


    public function get($name)
    {
        $name = \explode('.', $name);
        
        $module = \getModule();
        if (!isset($this->_config[$module][$name[0]])) {
//            加载配置文件
            $path = APP_ROOT . $module . DS . 'conf' . DS . $name[0] . '.php';
            if (!\file_exists($path)) {
                $this->triggerThrowable('conf file ' . $name[0] . ' not exists', 500);
            }

            $this->_config[$module][$name[0]] = include $path;
        }

        $ret = $this->_config[$module];
        foreach ($name as $item) {
            $ret = $ret[$item] ?? '';
        }

        return $ret;
    }
}