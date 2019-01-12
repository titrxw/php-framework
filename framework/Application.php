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
            'cookie' => 'framework\\components\\cookie\\Cookie',
            'taskManager' => 'framework\\task\\Task'
        ];
        if (PHP_SAPI == 'cli') {
            $components['url'] = 'framework\\components\\url\\CliUrl';
        }
        $components = \array_merge($components, $this->_conf['addComponentsMap'] ?? []);
        $this->_container->addComponents(SYSTEM_APP_NAME, $components);

        unset($components);
    }

    public static function run($command = '')
    {
        $instance = new static(\require_file('framework/conf/base.php'));
        $result = '';
        $code = 500;
        try
        {
            $GLOBALS['ERROR'] = false;
            $GLOBALS['EXCEPTION'] = false;

            $container = Container::getInstance();
            $urlInfo = $container->getComponent(SYSTEM_APP_NAME, 'url')->run();
            if (DEBUG) {
                ob_start();
            }

            if ($urlInfo !== false) {
                $_SERVER['CURRENT_SYSTEM'] = $urlInfo['system'];
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
                if (\is_array($result)) {
                    $result = json_encode($result);
                }
                $container->getComponent(SYSTEM_APP_NAME, 'cookie')->send();
                if (DEBUG) {
                    $elseContent = \ob_get_clean();
                    if ($elseContent) {
                        if (is_array($elseContent)) {
                            $elseContent = json_encode($elseContent);
                        }
                        $result .= $elseContent;
                        unset($elseContent);
                    }
                }
            }
        }
        catch (\Throwable $e)
        {
            $code = $e->getCode() > 0 ? $e->getCode() : 500;
            $instance->handleThrowable($e);
            if (DEBUG) {
                $elseContent = \ob_get_clean();
                if ($elseContent) {
                    if (is_array($elseContent)) {
                        $elseContent = json_encode($elseContent);
                    }
                    $result .= $elseContent;
                    unset($elseContent);
                }
            }
        }

        if ($GLOBALS['EXCEPTION']) {
            DEBUG && $result .= $GLOBALS['EXCEPTION'];
            $container->getComponent(SYSTEM_APP_NAME, 'header')->setCode($code);
        }
        if ($GLOBALS['ERROR']) {
            DEBUG && $result .= $GLOBALS['ERROR'];
            $container->getComponent(SYSTEM_APP_NAME, 'header')->setCode($code);
        }

        $container->getComponent(SYSTEM_APP_NAME, 'response')->send($result);
    }
}