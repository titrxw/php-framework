<?php
///**
// * Created by PhpStorm.
// * User: rxw
// * Date: 2017/10/30
// * Time: 21:00
// */
//namespace framework\components\cache;
//class Memcached extends Cache implements CacheInterface
//{
//    protected function init()
//    {
//        if (!extension_loaded('memcached')) {
//            throw new \Exception('not support: memcached', 500);
//        }
//        $this->_handle = new \Memcached;
//        $this->_handle->setOptions($this->_appConf);
//
//        $username = $this->getValueFromConf('username');
//        $password = $this->getValueFromConf('password');
//        if ($username !== '' || $password !== '') {
//            $this->_handle->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
//            $this->_handle->setSaslAuthData($username, $password);
//        }
//    }
//    /**
//     * 判断缓存
//     * @access public
//     * @param string $name 缓存变量名
//     * @return bool
//     */
//    public function has($name)
//    {
//        return $this->_handle->get($this->getCacheKey($name)) ? true : false;
//    }
//
//    /**
//     * 读取缓存
//     * @access public
//     * @param string $name 缓存变量名
//     * @param mixed  $default 默认值
//     * @return mixed
//     */
//    public function get($name, $default = false)
//    {
//        $value = $this->_handle->get($this->getCacheKey($name));
//        if (false !== $value) {
//            return $default;
//        }
//
//        $jsonData = json_decode($value, true);
//        return (null === $jsonData) ? $value : $jsonData;
//    }
//
//    /**
//     * 写入缓存
//     * @access public
//     * @param string    $name 缓存变量名
//     * @param mixed     $value  存储数据
//     * @param integer   $expire  有效时间（秒）
//     * @return bool
//     */
//    public function set($name, $value, $expire = null)
//    {
//        if (is_null($expire)) {
//            $expire = $this->_appConf['expire'];
//        }
//        $value = (is_object($value) || is_array($value)) ? json_encode($value) : $value;
//        return $this->_handle->set($this->getCacheKey($name), $value, $expire);
//    }
//
//    /**
//     * 自增缓存（针对数值缓存）
//     * @access public
//     * @param string    $name 缓存变量名
//     * @param int       $step 步长
//     * @return false|int
//     */
//    public function inc($name, $step = 1)
//    {
//        return $this->_handle->increment($this->getCacheKey($name), $step);
//    }
//
//    /**
//     * 自减缓存（针对数值缓存）
//     * @access public
//     * @param string    $name 缓存变量名
//     * @param int       $step 步长
//     * @return false|int
//     */
//    public function dec($name, $step = 1)
//    {
//        $key   = $this->getCacheKey($name);
//        $value = $this->_handle->get($key) - $step;
//        $res   = $this->_handle->set($key, $value);
//        if (!$res) {
//            return false;
//        } else {
//            return $value;
//        }
//    }
//
//    /**
//     * 删除缓存
//     * @param    string  $name 缓存变量名
//     * @param bool|false $ttl
//     * @return bool
//     */
//    public function rm($name, $ttl = false)
//    {
//        $key = $this->getCacheKey($name);
//        return false === $ttl ?
//            $this->_handle->delete($key) :
//            $this->_handle->delete($key, $ttl);
//    }
//
//    /**
//     * 清除缓存
//     * @access public
//     * @return bool
//     */
//    public function clear()
//    {
//        return $this->_handle->flush();
//    }
//}