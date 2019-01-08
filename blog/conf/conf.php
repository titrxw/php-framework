<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 17-8-26
 * Time: 下午8:50
 */
return array(
    'composer' => array(
        'meedo' => function (array $params) {
			var_dump($params);
            return new \Medoo\Medoo($params);      //这里测试composer的加载
        }
    ),
    'addComponentsMap' => array(
        'validate' => 'framework\\components\\validate\\Validate',
        'password' => 'framework\\components\\security\\Password',
        'uniqueid' => 'framework\\components\\uniqueid\\UniqueId',
        'redis' => 'framework\\components\\cache\\Redis',
        'aes' => 'framework\\components\\security\\Aes7',
        'token' => 'framework\\components\\security\\Token'
    ),
    'components' => array(
        'aes' => [
            'key' => 'gtifhaisdgthrehagtifhaisdgthreha',
            'iv' => 'gtifhaisdgthreha',
            'code' => 'hex'
        ],
        'controller' => [
            'controller' => [
                'prefix' => '',
                'suffix' => ''
            ],
            'action' => [
                'prefix' => '',
                'suffix' => 'Api'
            ]
        ],
        'model' => array(
            'db' => 'meedo'
        ),
        'meedo' => array(
            'database_type' => 'mysql',
            'database_name' => 'contract_test',
            'server' => 'do.zh5j.cn',
            'username' => 'root',
            'password' => 'ystctest',
            // [optional]
            'charset' => 'utf8',
            'port' => 3306,
            // [optional] Table prefix
            'prefix' => 'lease',
        
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
       'session' => array(
            'cookie' => 'cookie',
           'redis' => array(
               'session_name' => '', // sessionkey前缀
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
       'view' => array(
           'templatePath' => 'view',
           'cachePath' => 'runtime/viewCache',
           'compilePath' => 'runtime/compile',
           'viewExt' => '.html',
           'isCache' => false,
           'cacheExpire' => 3600,
           'leftDelimiter' => '{',
           'rightDelimiter' => '}'
       )
    )
);