<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/10/25
 * Time: 20:53
 */

namespace framework\components\security;
use framework\base\Component;

class Aes extends Component
{
    protected $_handle = null;
    /**
     * 加密所用的算法
     * @var unknown
     */
    protected $_cipher = MCRYPT_RIJNDAEL_128;
    /**
     * 加密的模式         cbc在相同数据的处理上比ecb安全         特别是对图片
     * @var unknown
     */
    protected $_mode = MCRYPT_MODE_CBC;

    /**
     * 数据填充?
     * @var unknown
     */
    protected $_isPad = true;
    /**
     * 秘钥
     * @var unknown
     */
    protected $_secretKey = '';
    /**
     * 偏移向量
     * @var unknown
     */
    protected $_iv = '';

    /**
     * 加密结果编码
     */
    protected $_codeType = 'base64';

    protected function init()
    {
        if (!extension_loaded('php_mcrypt')) {
            throw new \Error('not support: php_mcrypt');
        }
    }

    /**
     * 设置加密算法
     * @param unknown $cipher
     */
    public function setCipher($cipher)
    {
        $this->_cipher = $cipher;
        return $this;
    }

    /**
     * 设置加密模式
     * @param unknown $mode
     */
    public function setMode($mode)
    {
        $this->_mode = $mode;
        return $this;
    }
    /**
     * 设置便宜向量
     * @param unknown $iv
     */
    public function setIv($iv)
    {
        $this->_iv = $iv;
        return $this;
    }
    /**
     * 设置秘钥
     * @param unknown $key
     */
    public function setKey($key)
    {
        $this->_secretKey = $key;
        return $this;
    }

    public function setCodeType($type)
    {
        if ($type === 'base64' || $type === 'hex')
        {
            $this->_codeType = $type;
        }
        return $this;
    }

    protected function padOrUnPad($str, $ext)
    {
        if ($this->_isPad === true)
        {
            $func_name = 'pkcs5_' . $ext . 'pad';
            if (method_exists($this, $func_name))
            {
                $size = mcrypt_get_block_size($this->_cipher, $this->_mode);
                return $this->$func_name($str, $size);
            }
        }
        return $str;
    }

    /**
     * 填充
     * @param unknown $str
     */
    protected function pad($str)
    {
        return $this->padOrUnPad($str, '');
    }

    /**
     * 解除填充
     * @param unknown $str
     */
    protected function unPad($str)
    {
        return $this->padOrUnPad($str, 'un');
    }

    private function initHandle()
    {
        if(!$this->_handle)
        {
            $this->_handle = mcrypt_module_open($this->_cipher, '', $this->_mode, '');
            if ( empty($this->_iv)&&$this->_mode != MCRYPT_MODE_ECB )
            {
                $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($this->_handle), MCRYPT_RAND);
            }
            else
            {
                $iv = $this->_iv;
            }
            mcrypt_generic_init($this->_handle, $this->_secretKey, $iv);
        }
    }
    /**
     * 加密
     * @param unknown $str
     */
    public function encrypt($value)
    {
        $value = $this->pad($value);
        $this->initHandle();
        $cyper_text = mcrypt_generic($this->_handle , $value);
        if( $this->_codeType === 'hex'){
            $rt=bin2hex($cyper_text);
        }
        else
        {
            $rt=base64_encode($cyper_text);
        }
        unset($cyper_text, $value);
        return $rt;
    }

    public  function decrypt()
    {
        $this->initHandle();
        if($this->_codeType === 'hex')
            $decrypted_text = mdecrypt_generic(self::$_instance , $this->hex2bin($this->_value));
        else
            $decrypted_text = mdecrypt_generic(self::$_instance , base64_decode($this->_value));

        return $this->unPad($decrypted_text);
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

    protected function pkcs5_pad($text, $blocksize=16)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    protected function pkcs5_unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    public function __destruct()
    {
        if ($this->_handle)
        {
            mcrypt_generic_deinit($this->_handle);
            mcrypt_module_close($this->_handle);
        }
        $this->_handle=null;
    }
}