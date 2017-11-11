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

    protected function init()
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('not support: openssl', 500);
        }
        $this->_mode = $this->getValueFromConf('mode', 'aes-256-cbc');
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
        return base64_encode(openssl_random_pseudo_bytes($len/8));
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
        return base64_encode(openssl_random_pseudo_bytes(16));
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

    public function encrypt($data)
    {
        if (empty($data)){
            return false;
        }
        return base64_encode(openssl_encrypt($data, $this->getMode(), base64_decode($this->getKey()), OPENSSL_RAW_DATA, base64_decode($this->getIv())));
    }

    public function decrypt($data)
    {
        if (empty($data)){
            return false;
        }
        return openssl_decrypt(base64_decode($data), $this->getMode(), base64_decode($this->getKey()), OPENSSL_RAW_DATA, base64_decode($this->getIv()));
    }
}