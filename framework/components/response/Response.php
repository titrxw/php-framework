<?php
namespace framework\components\response;
use framework\base\Component;

class Response extends Component
{
    protected $_headers = array();
    protected $_code = 200;
    protected $_defaultType;
    protected $_defaultCharSet;
    protected $_contentTypes = array(
        'xml'  => 'application/xml,text/xml,application/x-xml',
        'json' => 'application/json,text/x-json,application/jsonrequest,text/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpg,image/jpeg,image/pjpeg',
        'gif'  => 'image/gif',
        'csv'  => 'text/csv',
        'text' => 'text/html,application/xhtml+xml,*/*'
    );

    protected function initHeader()
    {
        $this->_headers = array(
            'X-Powered-By' => 'esay-framework'
        );
    }

    protected function init()
    {
        $this->unInstall();
        $this->initHeader();
        $this->contentType('text');
    }

    public function noCache()
    {
        $this->addHeader('Cache-Control','no-store, no-cache, must-revalidate');
        $this->addHeader('Pragma','no-cache');
        header("Cache-Control: post-check=0, pre-check=0", false);
    }

    public function send($data)
    {
        http_response_code($this->_code);
        $this->sendHeader();
        echo $data;
    }

    public function addHeader($key, $header)
    {
        if(!empty($key) && !empty($header))
            $this->_headers[$key] = $header;
    }

    public function contentType($type, $charset = '')
    {
        $contentType = empty($this->_contentTypes[$type])?$this->_contentTypes[$this->getDefaultType()] : $this->_contentTypes[$type];
        $charset = empty($charset) ? $this->getDefaultCharSet(): $charset;
        $this->_headers['Content-Type'] = $contentType . '; charset=' . $charset;
    }

    protected function getDefaultType()
    {
        if(empty($this->_defaultType))
        {
            $this->_defaultType = $this->getValueFromConf('defaultType', 'text');
        }
        return $this->_defaultType;
    }

    protected function getDefaultCharSet()
    {
        if(empty($this->_defaultCharSet))
        {
            $this->_defaultCharSet = $this->getValueFromConf('charset', 'utf-8');
        }
        return $this->_defaultCharSet;
    }

    public function sendHeader()
    {
        foreach ($this->_headers as $key=>$item)
        {
            header($key . ':' . $item);
        }
        $this->initHeader();
        $this->_code = 200;
    }

    public function setCode($code)
    {
        $this->_code = $code;
    }
}