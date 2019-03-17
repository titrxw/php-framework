<?php
namespace framework\base;

abstract class Component extends Base
{
    protected $_uniqueId = '';

    public function __construct($conf = [])
    {
        $this->_conf = $conf;

        unset($conf);
    }

    final protected function getComponent($haver, $componentName,$params = [])
    {
        return Container::getInstance()->getComponent($haver, $componentName, $params);
    }

    final protected function unInstall($isComplete = false)
    {
        Container::getInstance()->unInstall(\getModule(), $this->_uniqueId, $isComplete);
    }

    final public function unInstallNow($isComplete = false)
    {
        $module = \getModule();
        if ($isComplete) {
            Container::getInstance()->destroyComponent($module, $this->_uniqueId);
        } else {
            Container::getInstance()->destroyComponentsInstance($module, $this->_uniqueId);
        }
    }

    final public function setUniqueId($name)
    {
        $this->_uniqueId = $name;
        $this->init();
    }
}
