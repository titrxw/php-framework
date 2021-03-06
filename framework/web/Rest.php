<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/2
 * Time: 12:11
 */
namespace framework\web;

abstract class Rest extends Api
{
    protected function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }
    
    public function __call($methods, $args)
    {
      $method = \strtolower($this->request->method());
      $methods = $method . \ucfirst($methods);
      return $this->$methods($args);
    }
}