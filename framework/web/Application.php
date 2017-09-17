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
        $this->_appConf['addComponentsMap'] = empty($this->_appConf['addComponentsMap']) ? array() : $this->_appConf['addComponentsMap'];
        parent::addBaseComponents();
        $components = array(
            'session' => 'framework\\components\\session\\Session',
            'view' => 'framework\\components\\view\\View',
            'cache' => 'framework\\components\\cache\\Redis',
            'Pdo' => 'framework\\components\\db\\Pdo',
            'log' => 'framework\\components\\log\\Log'
        );
        $this->_container->addComponents($components);
        $this->_container->addComponents($this->_appConf['addComponentsMap']);
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
        $url = $instance->getUrl()->run($server);

        ob_start();
        $result = $instance->getDispatcher()->run($url);
        $content = ob_get_clean();

        $instance->getResponse()->send($result.$content);
        unset($result,$content);
        $instance->finish();
        unset($default, $conf, $instance);
    }
}