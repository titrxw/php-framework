<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:50
 */
return array(
    'composer' => array(
//        'Logger' => function (array $params) {
//            return new \Monolog\Logger($params[0]);      //这里测试composer的加载
//        },
        'meedo' => function (array $params) {
            return new \Medoo\Medoo($params);      //这里测试composer的加载
        },
        'crawler' => function ($params) {
            return new Symfony\Component\DomCrawler\Crawler();
        }
    ),
    'addComponentsMap' => array(
        'validate' => 'framework\\components\\validate\\Validate',
        'password' => 'framework\\components\\security\\Password',
        'tokenBucket' => 'framework\\tokenbucket\\Bucket',
        'apireset' => 'blog\\lib\\ApiReset',
        'imgzip' => 'framework\\components\\imagic\\Imgzip',
        'uniqueid' => 'framework\\components\\uniqueid\\UniqueId',
        //'page' => 'framework\\components\\page\\Page',//如果使用api的话这里不需要
        //'view' => 'framework\\components\\view\\View',     //如果使用api的话这里不需要,
        'upload' => 'framework\\components\\upload\\NUpload',
        //'msgTask' => 'blog\\conf\\Task',
        //'crontabTask' => 'blog\\conf\\CrontabTask',
        'redis' => 'framework\\components\\cache\\Redis',
//        'sessionRedis' => 'framework\\components\\cache\\Redis',
//        'session' => 'framework\\components\\session\\Session',
        'captcha' => 'framework\\components\\captcha\\Captcha',
        //'crontab' => 'framework\\crontab\\Crontab'
    ), //该项因为设计上的问题暂时不添加
    'components' => array(
        'meedo' => array(
            'database_type' => 'mysql',
            'database_name' => 'lease',
            'server' => '127.0.0.1',
            'username' => 'root',
            'password' => '123456',
            // [optional]
            'charset' => 'utf8',
            'port' => 3306,
            // [optional] Table prefix
            'prefix' => 'lease_',
            'option' => [
                \PDO::ATTR_PERSISTENT => true
            ],
        
            // [optional] Enable logging (Logging is disabled by default for better performance)
            'logging' => true,
        ),
        'redis' => array(
            'host'         => '127.0.0.1', // redis主机
            'port'         => 6379, // redis端口
            'password'     => '', // 密码
            'select'       => 0, // 操作库
            'expire'       => 3600, // 有效期(秒)
            'timeout'      => 0, // 超时时间(秒)
            'persistent'   => true, // 是否长连接,
            'prefix' => ''
        ),
        'apireset' => array(
            'key' => '0a4df5rge6t6h8beg32g4',
            'step' => 5
        ),
//        'sessionRedis' => array(
//            'host'         => '127.0.0.1', // redis主机
//            'port'         => 6379, // redis端口
//            'password'     => '', // 密码
//            'select'       => 0, // 操作库
//            'expire'       => 3600, // 有效期(秒)
//            'timeout'      => 0, // 超时时间(秒)
//            'persistent'   => true, // 是否长连接,
//            'prefix' => ''
//        ),
//        'db' => array(
//            'db' => array(
//                'db1' => array(
//                    'type' => 'mysql',
//                    'dbName' => 'test',
//                    'user' => 'root',
//                    'password' => '123456',
//                    'host' => 'localhost:3306',
//                    'persistent' => true
//                )
//            )
//        ),
//        'session' => array(
//              'cookie' => 'cookie',
//            'redis' => array(
//                'session_name' => '', // sessionkey前缀
//            ),
//            'httpOnly'=> true,
//            'driver'=> array(
//                'type' => 'redis',
//                'name' => 'sessionRedis'
//            ),
//            'path'=> '',
//            'name' => 'EASYSESSION',
//            'prefix' => 'easy-'
//        ),
        'upload' => array(
            'accept' => array(
                'jpg',
                'png'
            ),
            'prefx' => 'rxwyun_102410_ngf_'
        ),
        'captcha' => array(
            'height' => 70,
            'width' => 200,
            'num' => 5,
            'type' => 'png'   //png jpg gif
        ),
        'tokenBucket' => [
            'buckets' => [
                'request' => [
                    'class' => 'framework\\tokenbucket\\Request',
                    'auto' => true,
                    'conf' => [
                        'max' => 20,
                        'key' => 'bucket_b_request',
                        'range' => 60, //单位s
                        'addStep' => 0,
                        'timeStep' => 3//单位s
                    ]
                ],
                'mobile' => [
                    'class' => 'framework\\tokenbucket\\Mobile',
                    'auto' => false,
                    'conf' => [
                        'max' => 5,
                        'key' => 'mobile',
                        'range' => 60, //单位s
                        'addStep' => 0,
                        'timeStep' => 3//单位s
                    ]
                ],
                'ip' => [
                    'class' => 'framework\\tokenbucket\\Ip',
                    'auto' => false,
                    'conf' => [
                        'max' => 3,
                        'key' => 'ip',
                        'range' => 5, //单位s
                        'addStep' => 0,
                        'timeStep' => 3//单位s
                    ]
                ]
            ],
        ]
    )
);