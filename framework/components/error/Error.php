<?php
namespace framework\components\error;
use framework\base\Component;

class Error extends Component
{
    public  function handleError($code, $message, $file, $line)
    {
        var_dump(func_get_args());
        throw new \Exception(serialize(func_get_args()), 200);
    }
}