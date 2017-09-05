<?php
namespace framework\components\exception;
use framework\base\Component;

class Exception extends Component
{
    public  function handleException($exception)
    {
        var_dump($exception);
        $code = $exception->getCode();
        if ($code > 0)
        {
            http_response_code($code);
        }
        $this->getComponent('log')->save($this->formarMessage($exception));
    }

    protected function formarMessage($exception)
    {
        return " msg : " . $exception->getMessage() .
            " \r\n file : " . $exception->getFile().
            " \r\n line : " . $exception->getLine().
            " \r\n code : " . $exception->getCode().
            " \r\n trace : " . $exception->getTraceAsString();
    }
}