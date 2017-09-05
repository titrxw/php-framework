<?php
namespace framework\base;


class Application extends Base
{
    protected $_container;

    protected function beforeInit()
    {
        return true;
    }

    protected function init()
    {
        $this->beforeInit();
        $this->initEnv();
        $this->initContainer();
        $this->addBaseComponents();
        $this->setExceptionHandle();
        $this->setErrorHandle();
        $this->setShutDownHandle();
    }

    public static function run($conf)
    {
        unset($conf);
        return true;
    }

    protected function initEnv()
    {

    }

    protected function initContainer()
    {
        $conf = array(
            'default' => $this->_conf['components'],
            'app' => $this->_appConf['components']
        );

        $this->_container = new Container($conf);
        unset($conf,$this->_conf['components'], $this->_appConf['components']);
    }

    protected function addBaseComponents()
    {
        $components = array(
            'exception' => 'framework\\components\\exception\\Exception',
            'error' => 'framework\\components\\error\\Error',
            'shutdown' => 'framework\\components\\shutdown\\ShutDown',
            'log' => 'framework\\components\\log\\Log',
            'url' => 'framework\\components\\url\\Url',
            'dispatcher' => 'framework\\components\\dispatcher\\Dispatcher',
            'request' => 'framework\\components\\request\\Request',
            'response' => 'framework\\components\\response\\Response'
        );
        $this->_container->addComponents($components);
        unset($components);
    }

    protected function setErrorHandle()
    {
        set_error_handler(array($this->_container->getComponent('error'), 'handleError'));
    }

    protected function setExceptionHandle()
    {
        set_exception_handler(array($this->_container->getComponent('exception'), 'handleException'));
    }

    protected function setShutDownHandle()
    {
        register_shutdown_function(array($this->_container->getComponent('shutdown'), 'handleShutDown'));
    }

    protected function getUrl()
    {
        return $this->_container->getComponent('url');
    }

    protected function getDispatcher()
    {
        return $this->_container->getComponent('dispatcher');
    }

    protected function getResponse()
    {
        return $this->_container->getComponent('response');
    }

    protected function finish()
    {
        $this->_container->finish($this->_appConf['unInstallComponents']);
    }
}