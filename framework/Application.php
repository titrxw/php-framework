<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:45
 */
namespace framework;

use framework\base\Container;

class Application extends \framework\base\Application
{
    protected function addBaseComponents()
    {
        parent::addBaseComponents();

        $components = [
            'conf' => 'framework\\base\\Conf',
            'cookie' => 'framework\\components\\cookie\\Cookie'
        ];
        if (PHP_SAPI == 'cli') {
            $components['url'] = 'framework\\components\\url\\CliUrl';
        }
        $components = \array_merge($components, $this->_conf['addComponentsMap'] ?? []);
        $this->_container->addComponents(SYSTEM_APP_NAME, $components);

        unset($components);
    }

    protected function beforeInit()
    {
        $this->_conf['components'] = $this->_conf['components']??[];
        $this->_appConf['components'] = [];
        $this->_conf['composer'] = $this->_conf['composer']??[];
        $this->_appConf['composer'] = [];
    }

    public static function run($command = '')
    {
        $conf = [
            'default' =>  \require_file('framework/conf/base.php'),
            'app' => []
        ];
        $instance = new static($conf);
        $result = '';
        try
        {
            $container = Container::getInstance();
            
            $urlInfo = $container->getComponent(SYSTEM_APP_NAME, 'url')->run();
            $_SERVER['CURRENT_SYSTEM'] = $urlInfo['system'];
            if (DEBUG) {
                ob_start();
            }

            if ($urlInfo !== false) {
                // 初始化配置项
                if (!$container->appHasComponents($urlInfo['system'])) {
//                这里现在还缺少文件系统
                    $appConf = \require_file($urlInfo['system'] . '/conf/conf.php');
                    $container->addComponents($urlInfo['system'], $appConf['addComponentsMap'] ?? []);
                    $container->setAppComponents($urlInfo['system'] ,array(
                        'components' => $appConf['components'] ?? [],
                        'composer' => $appConf['composer'] ?? []
                    ));
                    unset($appConf);
                }

                $result = $container->getComponent(SYSTEM_APP_NAME, 'dispatcher')->run($urlInfo);
                $container->getComponent(SYSTEM_APP_NAME, 'cookie')->send();
                if (DEBUG) {
                    $elseContent = \ob_get_clean();
                    if ($elseContent) {
                        if (is_array($elseContent)) {
                            $elseContent = json_encode($elseContent);
                        }
                        $container->getComponent(SYSTEM_APP_NAME, 'response')->send($elseContent);
                        unset($elseContent);
                    }
                }
                $container->getComponent(SYSTEM_APP_NAME, 'response')->send($result);
                unset($result);
            }
        }
        catch (\Throwable $e)
        {
            $code = $e->getCode() > 0 ? $e->getCode() : 500;
            $response = $container->getComponent(SYSTEM_APP_NAME, 'response');
            $response->setCode($code);
            if (DEBUG) {
                $result = $e->getMessage() . "\n trace: " . $e->getTraceAsString();
            }
            $response->send($result);
            $instance->handleThrowable($e);
            unset($default, $conf, $instance);

        }
    }
}