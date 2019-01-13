<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2019/1/9
 * Time: 20:53
 */
namespace permiss\model;

use framework\base\Model;

class Operate extends Model
{
    protected $_table = 'operate';

    public function save($data)
    {
        $exists = $this->db()->get($this->_table, 'id', ['name' => $data['name'], 'mid' => $data['mid']]);
        if ($exists) {
            return false;
        }

        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            if (!empty($data['create_time'])) unset($data['create_time']);
            if (!empty($data['is_delete'])) unset($data['is_delete']);

            $result = $this->db()->update($this->_table, $data, ['id' => $id]);
            //task执行强制对应用户下线
        } else {
            $data['unid'] = 'o-' . uniqueId();
            $data['create_time'] = time();
            $result = $this->db()->insert($this->_table, $data);
            //task执行强制对应用户下线
        }

        if ($result->rowCount() > 0) {
            return true;
        }

        return false;
    }

    public function getAll()
    {
        return $this->db()->select($this->_table, '*', ['is_delete' => 0]);
    }

    public function get($id)
    {
        return $this->db()->get($this->_table, '*', ['id' => $id, 'is_delete' => 0]);
    }

    public function delete($id)
    {
        if (!id) {
            return true;
        }
        $result = $this->db()->update($this->_table, ['is_delete' => 1], ['id' => $id]);
        if ($result->rowCount() > 0) {
            //task执行强制对应用户下线
            return true;
        }
        return false;
    }
}