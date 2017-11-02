<?php
namespace framework\components\shutdown;
use framework\base\Component;

class ShutDown extends Component
{
    public function handleShutDown()
    {
        $error = error_get_last();
    }
}