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
        $componenets = array(
            'session' => 'framework\\components\\session\\Session',
            'db' => 'framework\\components\\db\\Pdo',
            'validate' => 'framework\\components\\validate\\Validate',
            'view' => 'framework\\components\\view\\View'
        );
        $this->_container->addComponents($componenets);
        $this->_container->addComponents($this->_appConf['addComponentsMap']);
        unset($this->_appConf['addComponentsMap'], $componenets);
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
        var_dump(memory_get_usage());
        $result = $instance->getDispatcher()->run($url);
        $content = ob_get_clean();

        $instance->getResponse()->send($result.$content);
        unset($result,$content);
        $instance->finish();
        var_dump(memory_get_usage());
        unset($default, $conf, $instance);
    }
}