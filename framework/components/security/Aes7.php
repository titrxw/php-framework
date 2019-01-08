<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/11/1
 * Time: 21:10
 */
namespace framework\components\security;
use framework\base\Component;

class Aes7 extends Component
{
    protected $_key;
    protected $_iv;
    protected $_mode;
    protected $_codeType;

    protected function init()
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('not support: openssl', 500);
        }
        $this->setKey($this->getValueFromConf('key', ''));
        $this->setIv($this->getValueFromConf('iv', ''));
        $this->setCodeType($this->getValueFromConf('code', 'base64'));
        $this->setMode($this->getValueFromConf('mode', 'aes-256-cbc'));
    }

    public function setCodeType($type)
    {
        if ($type === 'base64' || $type === 'hex')
        {
            $this->_codeType = $type;
        }
        return $this;
    }

    public function setKey($key)
    {
        if (empty($key)) {
            return false;
        }
        $this->_key = $key;
    }

    public function makeKey($len = 256)
    {
        if ($len !== 128 && $len !== 256) {
           return false;
        }
        return openssl_random_pseudo_bytes($len/8);
    }

    public function getKey()
    {
        if (empty($this->_key))
        {
            $this->_key = $this->makeKey(256);
        }
        return $this->_key;
    }

    public function setIv($iv)
    {
        if (empty($iv)) {
            return false;
        }
        $this->_iv = $iv;
    }

    public function makeIv()
    {
        return openssl_random_pseudo_bytes(16);
    }

    public function getIv()
    {
        if (empty($this->_iv)) {
            $this->_iv = $this->makeIv();
        }
        return $this->_iv;
    }

    public function setMode($mode)
    {
        if (empty($mode)) {
            return false;
        }
        $this->_mode = $mode;
    }

    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * 16进制转2进制
     * @param unknown $hexdata
     */
    protected function hex2bin($hexdata)
    {
        $bin="";
        for($i=0; $i<strlen($hexdata)-1; $i+=2)
        {
            /**
             * chr转换ascll到字符
             */
            $bin.=chr(hexdec($hexdata[$i].$hexdata[$i+1]));
        }
        unset($hexdata);
        return $bin;
    }

    public function encrypt($data)
    {
        if (empty($data)){
            return false;
        }
        $data = openssl_encrypt($data, $this->getMode(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
        if($this->_codeType === 'hex')
            $data = bin2hex($data);
        else
            $data=base64_encode($data);

        return $data;
    }

    public function decrypt($data)
    {
        if (empty($data)){
            return false;
        }
        if($this->_codeType === 'hex')
            $data = $this->hex2bin($data);
        else
            $data=base64_decode($data);
        return openssl_decrypt($data, $this->getMode(), $this->getKey(), OPENSSL_RAW_DATA, $this->getIv());
    }
}