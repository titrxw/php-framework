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

    public static function run($command = '')
    {
        $instance = new static(\require_file('framework/conf/base.php'));
        $result = '';
        try
        {
            $GLOBALS['ERROR'] = false;
            $GLOBALS['EXCEPTION'] = false;

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
                        $result .= $elseContent;
                        unset($elseContent);
                    }
                }
            }
        }
        catch (\Throwable $e)
        {
            $code = $e->getCode() > 0 ? $e->getCode() : 500;
            $container->getComponent(SYSTEM_APP_NAME, 'header')->setCode($code);
            if (DEBUG) {
                $result = $result ?? '';
                $result .= $e->getMessage() . "\n trace: " . $e->getTraceAsString();
                $result .= \ob_get_clean();
                $GLOBALS['EXCEPTION'] = false;
            }
            $instance->handleThrowable($e);
        }

        if (DEBUG) {
            if ($GLOBALS['EXCEPTION']) {
                $result .= $GLOBALS['EXCEPTION'];
            }
            if ($GLOBALS['ERROR']) {
                $result .= $GLOBALS['ERROR'];
            }
        }
        $container->getComponent(SYSTEM_APP_NAME, 'response')->send($result);
    }
}