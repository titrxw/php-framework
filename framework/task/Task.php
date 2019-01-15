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
        if ($obj && $obj instanceof BaseTask)
        {
            $obj->run($taskName, $params);
            unset($obj);
        }
        else
        {
            $this->triggerThrowable(new \Error('task at do: id: ' . $taskId . ' class: ' . $taskObj['class'] . 'not found or not instance BaseTask'.
                ' or action: ' .$taskObj['func'] . ' not found', 500));
        }
    }
}