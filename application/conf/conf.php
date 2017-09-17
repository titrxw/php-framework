<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:50
 */
return array(
    'composer' => array(
        'Logger' => function (array $params) {
            return new \Monolog\Logger($params[0]);
        }
    ),
    'addComponentsMap' => array(
        'validate' => 'framework\\components\\validate\\Validate',
        'page' => 'framework\\components\\page\\Page'
    ),
    'components' => array(
        'cache' => array(
            'host'         => '127.0.0.1', // redis主机
            'port'         => 6379, // redis端口
            'password'     => '', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 0, // 超时时间(秒)
            'persistent'   => true, // 是否长连接,
            'prefix' => ''
        ),
        'session' => array(
            'Redis' => array(
                'session_name' => '', // sessionkey前缀
            ),
            'httpOnly'=> true,
            'driver'=> '',
            'path'=> '',
            'name' => 'EASYSESSION',
            'prefix' => 'easy-'
        ),
        'Pdo' => array(
            'db' => array(
                'db1' => array(
                    'type' => 'mysql',
                    'dbName' => 'test',
                    'user' => 'root',
                    'password' => '123456',
                    'host' => 'localhost:3306',
                    'persistent' => true
                )
            )
        )
    )
);