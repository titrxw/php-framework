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
        },
        'wechat' => function ($params) {
            return new EasyWeChat\Foundation\Application($params);
        }
    ),
    'addComponentsMap' => array(
        'validate' => 'framework\\components\\validate\\Validate',
        'page' => 'framework\\components\\page\\Page',
        'upload' => 'framework\\components\\upload\\Upload',
        'captcha' => 'framework\\components\\captcha\\Captcha',
        'aes' => 'framework\\components\\security\\Aes',
        'aes7' => 'framework\\components\\security\\Aes7',
        'rsa' => 'framework\\components\\security\\Rsa',
        'sessionRedis' => 'framework\\components\\cache\\Redis',
        'curl' => 'framework\\components\\curl\\Curl'
    ),
    'components' => array(
        'redis' => array(
            'host'         => '127.0.0.1', // redis主机
            'port'         => 6379, // redis端口
            'password'     => '', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 0, // 超时时间(秒)
            'persistent'   => false, // 是否长连接,
            'prefix' => ''
        ),
        'sessionRedis' => array(
            'host'         => '127.0.0.1', // redis主机
            'port'         => 6379, // redis端口
            'password'     => '', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 0, // 超时时间(秒)
            'persistent'   => true, // 是否长连接,
            'prefix' => ''
        ),
//        'memcache' => array(
//            'servers' => array(
//                array('127.0.0.1:1121')
//            ),
//            'username' => '',
//            'password'     => '', // 密码
//            'expire'       => 3600, // 有效期(秒)
//            'timeout'      => 0, // 超时时间(秒)
//            'persistent'   => 'test', // 是否长连接,
//            'compress_threshold' => 1024,
//            'prefix' => ''
//        ),
        'session' => array(
            'redis' => array(
                'prefix' => 'test', // sessionkey前缀
            ),
            'httpOnly'=> true,
            'driver'=> array(
                'type' => 'redis',
                'name' => 'sessionRedis'
            ),
            'path'=> '',
            'name' => 'EASYSESSION',
            'prefix' => 'easy-'
        ),
        'db' => array(
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
        ),
        'upload' => array(
            'accept' => array(
                'jpg',
                'png'
            )
        ),
        'captcha' => array(
            'height' => 70,
            'width' => 200,
            'num' => 5,
            'type' => 'png'   //png jpg gif
        ),
        'curl' => array(
            'timeout' => 30,
            'retry' => 3,
            'ssl' => true
        )
    )
);