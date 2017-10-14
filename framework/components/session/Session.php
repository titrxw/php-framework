<?php
namespace framework\components\session;

use framework\base\Component;

class Session extends Component
{
    protected  $_prefix;
    protected  $_autoStart;
    protected $_requestSessioId;
    protected $_name;
    protected $_path;
    protected $_driver;
    protected $_driverHandle;
    protected $_isStart = false;


    /**
     * 设置或者获取session作用域（前缀）
     * @param string $prefix
     * @return string|void
     */
    public function getPrefix($prefix = '')
    {
        if (empty($prefix) && null !== $prefix) {
            return $this->getDefaultPrefix();
        } else {
            $this->_prefix = $prefix;
            return $this->_prefix;
        }
    }

    protected function getDefaultPrefix()
    {
        if(empty($this->_prefix))
        {
            $this->_prefix = $this->getValueFromConf('prefix',null);
        }
        return $this->_prefix;
    }

    /**
     * session初始化
     * @return void
     */
    protected function init()
    {
        // 启动session
        $this->_autoStart = $this->getValueFromConf('autoStart',true);
        if ($this->_autoStart === true && PHP_SESSION_ACTIVE != session_status()) {
            ini_set('session.auto_start', 0);
        }

        $this->_requestSessioId = $this->getValueFromConf('requestSessionId','session');
        if (!empty($this->_requestSessioId) && !empty($_REQUEST[$this->_requestSessioId])) {
            session_id($_REQUEST[$this->_requestSessioId]);
        }

        $this->_name = $this->getValueFromConf('name','PHPSESSION');
        if (!empty($this->_name)) {
            session_name($this->_name);
        }

        $this->_path = $this->getValueFromConf('path','');
        if (!empty($this->_path)) {
            session_save_path($this->_path);
        }

        $httponly = $this->getValueFromConf('httpOnly',true);
        if ($httponly === true) {
            ini_set('session.cookie_httponly',$httponly);
        }

        $this->_driver = $this->getValueFromConf('driver','');
    }

    public function start()
    {
        if($this->_isStart === true) return true;
        if (!empty($this->_driver)) {
            // 读取session驱动
            $driverClass = 'framework\\components\\session\\driver\\' . $this->_driver;
            // 检查驱动类
            if (class_exists($driverClass))
            {
                $this->_driverHandle = new $driverClass(empty($this->_appConf[$this->_driver])?array():$this->_appConf[$this->_driver]);
                if(!session_set_save_handler($this->_driverHandle))
                {
                    unset($this->_driverHandle);
                    throw new \Error('session set handle failed',500);
                }
            }
        }
        if ($this->_autoStart)
        {
            session_start();
            $this->_isStart = true;
        }
    }

    /**
     * session设置
     * @param string        $name session名称
     * @param mixed         $value session值
     * @param string|null   $prefix 作用域（前缀）
     * @return void
     */
    public function set($name, $value = '', $prefix = null)
    {
        if(!$this->_isStart || empty($name)) return false;

        $prefix = !is_null($prefix) ? $prefix : $this->getPrefix();
        if ($prefix) {
            $_SESSION[$prefix][$name] = $value;
        } else {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * session获取
     * @param string        $name session名称
     * @param string|null   $prefix 作用域（前缀）
     * @return mixed
     */
    public function get($name = '', $prefix = null)
    {
        if(!$this->_isStart) return false;
        $prefix = !is_null($prefix) ? $prefix : $this->getPrefix();

        if ('' == $name) {
            // 获取全部的session
            $value = $prefix ? (!empty($_SESSION[$prefix]) ? $_SESSION[$prefix] : []) : $_SESSION;
        } elseif ($prefix) {
            $value = isset($_SESSION[$prefix][$name]) ? $_SESSION[$prefix][$name] : null;
        } else {
            $value = isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        }
        return $value;
    }

    /**
     * 删除session数据
     * @param string|array  $name session名称
     * @param string|null   $prefix 作用域（前缀）
     * @return void
     */
    public function delete($name, $prefix = null)
    {
        if(!$this->_isStart) return false;
        $prefix = !is_null($prefix) ? $prefix : $this->getPrefix();

        if (is_array($name)) {
            foreach ($name as $key) {
                $this->delete($key, $prefix);
            }
        }  else {
            if ($prefix) {
                unset($_SESSION[$prefix][$name]);
            } else {
                unset($_SESSION[$name]);
            }
        }
    }

    /**
     * 清空session数据
     * @param string|null   $prefix 作用域（前缀）
     * @return void
     */
    public function clear($prefix = null)
    {
        if(!$this->_isStart) return false;
        $prefix = !is_null($prefix) ? $prefix : $this->getPrefix();

        if ($prefix) {
            unset($_SESSION[$prefix]);
        } else {
            $_SESSION = [];
        }
    }

    /**
     * 判断session数据
     * @param string        $name session名称
     * @param string|null   $prefix
     * @return bool
     */
    public function has($name, $prefix = null)
    {
        if(!$this->_isStart) return false;
        $prefix = !is_null($prefix) ? $prefix : $this->getPrefix();

        return $prefix ? isset($_SESSION[$prefix][$name]) : isset($_SESSION[$name]);
    }

    /**
     * 销毁session
     * @return void
     */
    public function destroy()
    {
        if($this->_isStart)
        {
            unset($_SESSION);
            session_unset();
            session_destroy();
            $this->finish();
            $this->_isStart = false;
        }
    }

    /**
     * 重新生成session_id
     * @param bool $delete 是否删除关联会话文件
     * @return void
     */
    public function regenerate($delete = false)
    {
        session_regenerate_id($delete);
    }

    /**
     * 暂停session
     * @return void
     */
    public function pause()
    {
        session_write_close();
        $this->finish();
        $this->_isStart = false;
    }

    public function finish()
    {
        unset($this->_driverHandle);
    }

    public function __destruct()
    {
        $this->destroy();
    }
}