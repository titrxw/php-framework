<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2019/1/10
 * Time: 21:58
 */

return [
    'menu' => [
        [
            "name"=>"组织结构管理",
            "icon"=>"hammer",
            "menu"=>[
                [
                    "name"=>"部门",
                    "icon"=>"",
                    "url"=>"/organization/department"
                ],
                [
                    "name"=>"职位",
                    "icon"=>"",
                    "url"=>"/organization/job"
                ]
            ]
        ],
        [
            "name"=>"模块管理",
            "icon"=>"hammer",
            "menu"=>[
                [
                    "name"=>"模块",
                    "icon"=>"",
                    "url"=>"/module"
                ],
                [
                    "name"=>"操作",
                    "icon"=>"",
                    "url"=>"/module/operate"
                ]
            ]
        ],
        [
            "name"=>"角色管理",
            "icon"=>"hammer",
            "menu"=>[
                [
                    "name"=>"角色",
                    "icon"=>"",
                    "url"=>"/organization/role"
                ]
            ]
        ],
        [
            "name"=>"用户管理",
            "icon"=>"hammer",
            "menu"=>[
                [
                    "name"=>"用户",
                    "icon"=>"",
                    "url"=>"/organization/user"
                ]
            ]
        ]
                ],
                'operate' => [
                    'permiss/System/menu',
                    'permiss/Department/list',
                    'permiss/Department/save',
                    'permiss/Department/get',
                    'permiss/Department/delete',
                    'permiss/Job/list',
                    'permiss/Job/save',
                    'permiss/Job/get',
                    'permiss/Job/delete',
                    'permiss/Module/list',
                    'permiss/Module/save',
                    'permiss/Module/get',
                    'permiss/Module/delete',
                    'permiss/Operate/list',
                    'permiss/Operate/save',
                    'permiss/Operate/get',
                    'permiss/Operate/delete'
                ]
];