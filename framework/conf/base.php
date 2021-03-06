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
        'doc' => 'framework\\base\\Documentor'
    ),
    'components' => array(
        'dispatcher'=> [
            'route_filter' => true
        ],
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
            'systems' => array('blog', 'application1', 'permiss')
        ),
        'resquest' => array(
            'separator' => '/',
            'url' => 'url'
        ),
        'response' => array(
            'defaultType' => 'text',
            'charset' => 'utf-8'
        )
    )
);

