<?php
namespace framework\web;


class Model extends \framework\base\Model
{
    protected function addTask($className, $funcName, $params)
    {
        $this->taskManager->addTask($className, $funcName, $params);
    }
}