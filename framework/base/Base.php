<?php
namespace framework\base;

abstract class Base
{
    protected $_conf;
    protected $_appConf;

    public function __construct($conf = array())
    {
        $this->_conf = empty($conf['default'])? array() : $conf['default'];
        $this->_appConf = empty($conf['app'])? array() : $conf['app'];
        $this->init();
        unset($conf);
    }

    public function getConf()
    {
        return $this->_conf;
    }

    public function getAppConf()
    {
        return $this->_appConf;
    }

    protected function getValueFromConf($key, $default = '')
    {
        $tmpKey = explode('.',$key);
        if (count($tmpKey) > 1)
        {
            $_confValue = empty($this->_conf[$tmpKey[0]]) ? '' : $this->_conf[$tmpKey[0]] ;
            $_appConfValue = empty($this->_appConf[$tmpKey[0]]) ? '' : $this->_appConf[$tmpKey[0]];
            unset($tmpKey[0]);
            foreach ($tmpKey as $item)
            {
                if (!empty($_confValue))
                {
                    $_confValue = $_confValue[$item];
                }
                if (!empty($_appConfValue))
                {
                    $_appConfValue = $_appConfValue[$item];
                }
            }
        }
        else
        {
            $_confValue = empty($this->_conf[$key]) ? '' : $this->_conf[$key];
            $_appConfValue = empty($this->_appConf[$key]) ? '' : $this->_appConf[$key];
        }
        unset($tmpKey);

        return empty($_appConfValue) ?
            (empty($_confValue) ? $default : $_confValue)
            : $_appConfValue;
    }

    protected function init()
    {
        return true;
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}