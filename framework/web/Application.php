<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:45
 */
namespace framework\web;

class Application extends \framework\base\Application
{
    protected function addBaseComponents()
    {
        parent::addBaseComponents();
        $this->_appConf['addComponentsMap'] = empty($this->_appConf['addComponentsMap']) ? array() : $this->_appConf['addComponentsMap'];
        $components = array(
            'session' => 'framework\\components\\session\\Session',
            'view' => 'framework\\components\\view\\View',
            'cache' => 'framework\\components\\cache\\Redis',
            'db' => 'framework\\components\\db\\Pdo',
            'log' => 'framework\\components\\log\\Log',
            'redis' => 'framework\\components\\cache\\Redis'
        );
        $components = array_merge($components, $this->_appConf['addComponentsMap']);
        $this->_container->addComponents($components);

        unset($this->_appConf['addComponentsMap'], $components);
    }

    protected function beforeInit()
    {
        $this->_conf['components'] = empty($this->_conf['components']) ? array() : $this->_conf['components'];
        $this->_appConf['components'] = empty($this->_appConf['components']) ? array() : $this->_appConf['components'];
    }

    public static function run($conf)
    {
        $instance = new Application($conf);
        $server = $_SERVER;
        $result = '';
        try
        {
            $url = $instance->getUrl()->run($server);
            $result = $instance->getDispatcher()->run($url);
            $instance->getResponse()->send($result);
            unset($result,$content);
        }
        catch (\Exception $e)
        {
            $code = $e->getCode() > 0 ? $e->getCode() : 404;
            $response = $instance->getResponse();
            $response->setCode($code);
            if (DEBUG) {
                $result = $e->getMessage() . "\n trace: " . $e->getTraceAsString();
            }
            $response->send($result);
            unset($default, $conf, $instance);
            throw $e;
        }
        catch (\Error $e)
        {
            $code = $e->getCode() > 0 ? $e->getCode() : 500;
            $response = $instance->getResponse();
            $response->setCode($code);
            if (DEBUG) {
                $result = $e->getMessage() . "\n trace: " . $e->getTraceAsString();
            }
            $response->send($result);
            unset($default, $conf, $instance);
            throw $e;
        }
    }
}