<?php
namespace framework\base;
use framework\traits\Throwable;


abstract class Base
{
    use Throwable;

    protected $_conf;

    public function __construct($conf = [])
    {
        $this->_conf = $conf;
        $this->init();
    }

    final public function getConf()
    {
        return $this->_conf;
    }

    final protected function getValueFromConf($key, $default = '')
    {
        if (!isset($this->{$key})) {
            $tmpKey = \explode('.',$key);
            if (\count($tmpKey) > 1)
            {
                $_confValue = empty($this->_conf[$tmpKey[0]]) ? null : $this->_conf[$tmpKey[0]];
                unset($tmpKey[0]);
                foreach ($tmpKey as $item)
                {
                    if ($_confValue)
                    {
                        $_confValue = $_confValue[$item] ?? null;
                    }
                }
            }
            else
            {
                $_confValue = !isset($this->_conf[$key]) ? null : $this->_conf[$key];
            }
            unset($tmpKey);

            $this->{$key} =  (!isset($_confValue) ? $default : $_confValue);
        }

        return $this->{$key};
    }

    protected function init()
    {
        return true;
    }
}