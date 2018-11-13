<?php
namespace framework\base;

class Application extends Base
{
    protected $_container;

    protected function beforeInit()
    {
        $this->_conf['components'] = $this->_conf['components']??[];
        $this->_conf['composer'] = $this->_conf['composer']??[];
        return true;
    }

    protected function init()
    {
        $this->initEnv();
        $this->beforeInit();
        $this->initContainer();
        $this->addBaseComponents();
        $this->setExceptionHandle();
        $this->setErrorHandle();
        $this->setShutDownHandle();
    }

    public static function run($command = '')
    {
        return true;
    }

    public function initEnv()
    {
        \define('ISSWOOLE', false);
        \define('FRAMEWORK_NAME', 'framework');
        \define('IS_CLI', PHP_SAPI == 'cli' ? true : false);


        \date_default_timezone_set('PRC');

        if(!\defined('DEBUG'))
            \define('DEBUG',true);
            
        \define('FAVICON','favicon.ico');
        \define('SYSTEM_WORK_ID', \getmypid());
        \define('SYSTEM_CD_KEY',\GetMacAddr(PHP_OS));
        \define('SYSTEM_APP_NAME', 'APP');

        if (\file_exists(APP_ROOT. 'vendor/autoload.php')) {
            \define('COMPOSER', true);
            \require_file('vendor/autoload.php');
        } else {
            \define('COMPOSER', false);
        }
    }

    protected function initContainer()
    {
        $this->_container = new Container($this->_conf['components']);

        if (COMPOSER)
        {
//            系统的composer
            $this->_container->setComposer(new Composer($this->_conf['composer']));
        }

        unset(
            $this->_conf['components'],
            $this->_conf['composer']
        );
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
            'header' => 'framework\\components\\response\\Header',
            'response' => 'framework\\components\\response\\Response'
        );
        $this->_container->addComponents(SYSTEM_APP_NAME, $components);
        unset($components);
    }

    protected function setErrorHandle()
    {
        \set_error_handler(array($this->_container->getComponent(SYSTEM_APP_NAME, 'error'), 'handleError'));
    }

    protected function setExceptionHandle()
    {
        \set_exception_handler(array($this->_container->getComponent(SYSTEM_APP_NAME, 'exception'), 'handleException'));
    }

    protected function setShutDownHandle()
    {
        \register_shutdown_function(array($this->_container->getComponent(SYSTEM_APP_NAME, 'shutdown'), 'handleShutDown'));
    }
}
