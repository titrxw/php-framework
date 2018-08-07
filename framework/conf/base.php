<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午9:33
 */
return array(
    'composer' => array(
        'sendfile' => function (array $params) {
            return new \diversen\sendfile();
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
            'defaultSystem' => 'blog',
            'defaultSystemKey' => 's',
            'controllerKey' => 'm',
            'actionKey' => 'act',
            'defaultController' => 'index',
            'defaultAction' => 'index',
            'systems' => array('blog', 'application1', 'blog')
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

