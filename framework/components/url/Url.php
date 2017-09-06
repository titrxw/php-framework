<?php
namespace framework\components\url;
use framework\base\Component;

class Url extends Component
{
    private $_server;
    protected $_defaultType;
    protected $_defaultController;
    protected $_defaultAction;
    protected $_defaultControllerKey;
    protected $_defaultActionKey;
    protected $_defaultSeparator;
    protected $_currentModule;

    public function run($args = array())
    {
        $this->_server = $args;
        unset($args);
        $this->_currentModule = $this->formatUrl();
        return $this->_currentModule;
    }

    public function getServer()
    {
        return $this->_server;
    }

    public function getCurrentModule()
    {
        return $this->_currentModule;;
    }

    protected function formatUrl()
    {
        $type = $this->getType();
        if ($type === '?')
        {
            return array(
                'controller' => empty($_GET[$this->getDefaultControllerKey()]) ? $this->getDefaultController() : $_GET[$this->getDefaultControllerKey()],
                'action' => empty($_GET[$this->getDefaultActionKey()]) ? $this->getDefaultAction() : $_GET[$this->getDefaultActionKey()]
            );
        }
        else
        {
            $query = $this->_server['QUERY_STRING'];
            $tmpQuery = explode($this->getSeparator(), $query);

            $urlInfo =  array(
                'controller' => empty($tmpQuery[0]) ? $this->getDefaultController() : $tmpQuery[0],
                'action' => empty($tmpQuery[1]) ? $this->getDefaultAction() : $tmpQuery[1]
            );

            $count = count($tmpQuery);
            $_GET = array();
            for($i=2;$i < $count; $i+=2)
            {
                $_GET[$tmpQuery[$i]] = !isset($tmpQuery[$i+1]) ?  '' : $tmpQuery[$i+1];
            }
            unset($tmpQuery);
            return $urlInfo;
        }
    }

    public function getHost()
    {
        return $this->_server['HTTP_HOST'];
    }

    public function getUrl()
    {
        return $this->_server['PHP_SELF'];
    }

    public function getResquestUrl()
    {
        return $this->_server['REQUEST_URI'];
    }

    public function getType()
    {
        if(empty($this->_defaultType))
        {
            $this->_defaultType = $this->getValueFromConf('type', '?');

            if(!in_array($this->_defaultType,array('/','?'))) {
                $this->_defaultType = '?';
            }
        }
        return $this->_defaultType;
    }

    protected function getSeparator()
    {
        if(empty($this->_defaultSeparator))
        {
            $this->_defaultSeparator = $this->getValueFromConf('separator', '/');
        }
        return $this->_defaultSeparator;
    }

    protected function getDefaultController()
    {
        if (empty($this->_defaultController))
        {
            $this->_defaultController = $this->getValueFromConf('defaultController', 'index');
        }
        return $this->_defaultController;
    }

    protected function getDefaultAction()
    {
        if (empty($this->_defaultAction))
        {
            $this->_defaultAction = $this->getValueFromConf('defaultAction', 'index');
        }
        return $this->_defaultAction;
    }

    protected function getDefaultControllerKey()
    {
        if (empty($this->_defaultControllerKey))
        {
            $this->_defaultControllerKey = $this->getValueFromConf('controllerKey', 'm');
        }
        return $this->_defaultControllerKey;
    }

    protected function getDefaultActionKey()
    {
        if(empty($this->_defaultActionKey))
        {
            $this->_defaultActionKey = $this->getValueFromConf('actionKey', 'act');
        }
        return $this->_defaultActionKey;
    }
}