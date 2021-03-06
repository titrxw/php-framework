<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 18-5-7
 * Time: 下午10:00
 */

namespace framework\components\url;

class CliUrl extends Url
{
    protected function formatUrl()
    {
        global $argv;
        \array_shift($argv);
        if (!empty($argv[0]) && \in_array($argv[0], $this->getValueFromConf('systems',[]))) {
            $system = $argv[0];
            \array_shift($argv);
        } else if (!empty($this->getValueFromConf('alias', [])[$argv[0]])){
            $system = $this->getValueFromConf('alias', [])[$argv[0]];
            \array_shift($argv);
        }  else {
            $system = $this->getValueFromConf('defaultSystem');
        }
        $system = \explode('.', $system);
        $version = $system[1] ?? '';
        $system = $system[0];
        

        foreach ($argv as $item) {
            if (\strpos($item, '=') > 0) {
                $item = \explode('=', $item);
                $_GET[$item[0]] = $item[1];
            }
        }

        
        $urlInfo =  array(
            'version' => $version,
            'method' => $_SERVER['REQUEST_METHOD'],
            'system' => $system,
            'controller' => empty($argv[0]) ? $this->getValueFromConf('defaultController', 'index') : $argv[0],
            'action' => empty($argv[1]) ? $this->getValueFromConf('defaultAction', 'index') : $argv[1]
        );

        $this->_curRoute = $urlInfo;
        unset($urlInfo, $argv);
        return $this->_curRoute;
    }

}