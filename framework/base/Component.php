<?php
namespace framework\base;

abstract class Component extends Base
{
    protected $_uniqueId = '';

    public function __construct($conf = array())
    {
        $this->_conf = empty($conf['default'])? array() : $conf['default'];
        $this->_appConf = empty($conf['app'])? array() : $conf['app'];

        unset($conf);
    }

    protected function getComponent($componentName)
    {
        return Container::getInstance()->getComponent($componentName);
    }

    protected function unInstall($isComplete = true)
    {
        Container::getInstance()->unInstall($this->_uniqueId, $isComplete);
    }

    public function setUniqueId($name)
    {
        $this->_uniqueId = $name;
        $this->init();
    }
}