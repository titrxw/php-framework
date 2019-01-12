<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-10-10
 * Time: 下午9:52
 */
namespace framework\task;

use framework\base\Component;

class Task extends Component
{
    public function addTask($taskClass, $taskName, $params = [], $taskId = -1)
    {
        if (!$taskClass || !$taskName || !is_string($taskClass) || !is_string($taskName))
        {
            return false;
        }
        $obj = Container::getInstance()->getComponent(\getModule(), $taskClass);
        return $obj->$taskName($params);
    }
}