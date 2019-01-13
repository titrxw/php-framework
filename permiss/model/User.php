<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/5
 * Time: 21:05
 */
namespace permiss\model;

use framework\base\Model;

class User extends Model
{
    public function register($name, $mobile, $password) 
    {
        $userInfo = $this->db()->get('user', 'id', [
            'mobile' => $mobile,
        ]);
        if ($userInfo) {
            return false;
        }
        $password = $this->password->setPassword($password)->MakeHashStr();
        $salt = $this->password->GetHashSalt();
        $user = [
            'unid' => 'u_' . \uniqueId(),
            'name' => $name,
            'mobile' => $mobile,
            'password' => $password,
            'salt' => $salt,
            'create_time' => time()
        ];
        $result = $this->db()->insert('user', $user);
        if ($result->rowCount() > 0) {
            unset($user['password'], $user['salt']);
            return $user;
        }
        return false;
    }

    public function login($mobile, $password)
    {
        $userInfo = $this->db()->get('user', ['unid', 'mobile', 'password','salt', 'name'], [
            'mobile' => $mobile,
        ]);
        if (!$userInfo) {
            return false;
        }
        $result = $this->password->setPassword($password)
        ->setSalt($userInfo['salt'])
        ->setHash($userInfo['password'])
        ->validate();
        if (!$result) {
            return false;
        }

        $userInfo['role'] = $this->db()->select('user_role','role_id', ['uid' => $userInfo['unid']]);

        unset($userInfo['password'], $userInfo['salt']);
        return $userInfo;
    }

    public function password($oldPwd, $newPwd, $uid)
    {
        $userInfo = $this->db()->get('user', ['password','salt'], ['union_id' => $uid]);
        if (!$userInfo) {
            return false;
        }
        $result = $this->password->setPassword($oldPwd)
        ->setSalt($userInfo['salt'])
        ->setHash($userInfo['password'])
        ->validate();
        if (!$result) {
            return false;
        }
        $password = $this->password->setPassword($newPwd)->MakeHashStr();
        $salt = $this->password->GetHashSalt();       
        
        $result = $this->db()->update('user', ['password' => $password,'salt' => $salt], ['union_id' => $uid]);
        if ($result->rowCount()) {
            return true;
        }
        return false;
    }

    public function getMenu($roleId)
    {
        if (!$roleId) {
            return [];
        }

        $mids = $this->db()->select('role_permiss',['[><]operate' => ['oid' => 'unid'], 'mid', ['rid' => $roleId, 'status' => 1, 'is_delete' => 0, 'GROUP' => 'mid']]);
        $menus = $this->db()->select('module', ['name', 'url', 'icon', 'pid', 'unid', 'path'], ['unid' => $mids]);
        $paths = \array_unique(\implode(',', \array_column($menus, 'path')));
        $pmenus = $this->db()->select('module', ['name', 'url', 'icon', 'pid', 'unid', 'path'], ['unid' => $paths]);
        $amenus = \array_merge($menus, $pmenus);

        return $this->tree->get($amenus, 'menu');
    }

    public function getOperate($roleId)
    {
        if (!$roleId) {
            return [];
        }
        
        return $this->db()->select('role_permiss',['[><]operate' => ['oid' => 'unid'], 'url', ['tid' => $roleId, 'status' => 1, 'is_delete' => 0]]);
    }
}