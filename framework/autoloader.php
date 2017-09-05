<?php
function autoload($classFile)
{
    $classFile = str_replace('\\','/',$classFile);
    include APP_ROOT . '/'.$classFile.'.php';
}

spl_autoload_register('autoload', true, true);