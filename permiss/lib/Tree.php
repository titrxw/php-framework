<?php

namespace permiss\lib;

use framework\base\Component;

class Tree extends Component
{
    protected $_pid;
    protected $_value;
    protected $_id;

    protected function init()
    {
        $this->_pid = $this->getValueFromConf('pid', 'pid');
        $this->_value = $this->getValueFromConf('value', '0');
        $this->_id = $this->getValueFromConf('id', 'unid');
        $this->unInstall();
    }

    public function get($data,$childKey = 'children')
    {
        if (!$data) {
            return [];
        }
        $data = array_combine(array_column($data,$this->_id), $data);

        foreach ($data as $item) {
            $data[$item[$this->_pid]][$childKey][] =& $data[$item[$this->_id]];
        }

        return $data[$this->_value];
    }
}