<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:11
 */
namespace framework\web;
use framework\base\Container;

abstract class Controller extends \framework\base\Controller
{
    static $_rules;

    protected function model($name)
    {
        $name = \ucfirst($name);
        $componentModel = \md5(\getModule() .'/model/'.$name);
        Container::getInstance()->addComponent(\getModule(), $componentModel,
            \getModule() .'\\model\\'. $name, Container::getInstance()->getComponentConf(\getModule(), 'model'));
//        在add之前设置当前model的conf
//        待开发
        return $this->getComponent(\getModule(), $componentModel);
    }

//    需要重写
    protected function validate()
    {
        if (isset(static::$_rules[$this->getAction()])) {
            $rules = static::$_rules[$this->getAction()];
        } else {
            $rules = [];
            $rule = $this->doc->parse($this, $this->getAction())->getTags('rule');
            foreach($rule as $item) {
                $item = \explode(' ', $item);
                $rules[$item[0]] = \end($item);
            }
        }
        
        if (empty($rules))
        {
            return true;
        }
        $data = array('get' => $this->request->get(),'post' => $this->request->post());
        $result = $this->validate->run($data, $rules);
        return $result;
    }

    protected function assign($key, $value = null)
    {
        $this->view->assign($key, $value);
    }

    protected function display($path = '')
    {
        return $this->view->display($path);
    }

    protected function ajax($data = null)
    {
        $this->header->noCache();
        $this->header->contentType('json');
        return $data;
    }

    protected function sendFile($path, $type = 'jpg')
    {
        if (!\file_exists($path))
        {
            $this->triggerThrowable(new \Error('sendfile: ' . $path . ' not exists', 500));
        }
        $this->header->contentType($type);
        $this->getComponent(SYSTEM_APP_NAME,'sendfile', [])->send($path);
        return true;
    }
}