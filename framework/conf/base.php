<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午9:33
 */
return array(
    'composer' => array(
        'Logger' => function (array $params) {
            return new \Monolog\Logger($params[0]);      //这里测试composer的加载
        },
        'meedo' => function (array $params) {
            return new \Medoo\Medoo($params);      //这里测试composer的加载
        }
    ),
    'addComponentsMap' => array(
        'msgTask' => 'blog\\conf\\Task'
    ),
    'components' => array(
        'log' => array(
            'path' => 'runtime/log/',
            'isLog' => true,
            'maxSize' => 2097152,
            'url' => 'url'
        ),
        'url' => array(
            'routerKey' => '',
            'type' => '/',
            'separator' => '/',
            'defaultSystem' => 'application',
            'defaultSystemKey' => 's',
            'controllerKey' => 'm',
            'actionKey' => 'act',
            'defaultController' => 'index',
            'defaultAction' => 'index',
            'systems' => array('application', 'application1', 'blog')
        ),
        'dispatcher' => array(
            'controller' => array(
                'prefix' => '',
                'suffix' => ''
            ),
            'action' => array(
                'prefix' => '',
                'suffix' => 'Api'
            )
        ),
        'resquest' => array(
            'separator' => '/',
            'url' => 'url'
        ),
        'response' => array(
            'defaultType' => 'text',
            'charset' => 'utf-8'
        ),
        'view' => array(
            'templatePath' => 'view',
            'cachePath' => 'runtime/viewCache',
            'compilePath' => 'runtime/compile',
            'viewExt' => '.html',
            'isCache' => false,
            'cacheExpire' => 3600,
            'leftDelimiter' => '{',
            'rightDelimiter' => '}'
        ),
        'upload' => array(
            'maxSize' => 2088960
        ),
        'captcha' => array(
            'height' => 70,
            'width' => 200,
            'num' => 5,
            'type' => 'png',   //png jpg gif,
            'response' => 'response'
        ),
        'page' => array(
            'url' => 'url'
        ),
        'model' => array(
            'db' => 'meedo'
        )
    )
);

