<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:55
 */
include __DIR__.'/autoloader.php';
if(!defined('APP_NAME'))
    define('APP_NAME','application');
define('APP_ROOT', dirname(dirname(__FILE__)));
define('$APP_PUBLIC_PATH', APP_ROOT.'/public/'.APP_NAME.'/');

$conf = array(
    'default' => require_once __DIR__.'/conf/base.php',
    'app' => require_once APP_ROOT. '/' .APP_NAME.'/conf/conf.php'
);
\framework\web\Application::run($conf);
unset( $conf);
