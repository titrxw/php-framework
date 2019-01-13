<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/5
 * Time: 21:05
 */
namespace permiss\model;

use framework\base\Model;

class Module extends Model
{
    protected $_table = 'module';

    public function save($data)
    {
        $exists = $this->db()->get($this->_table, 'id', ['name' => $data['name'], 'pid' => $data['pid']]);
        if ($exists) {
            return false;
        }

        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            if (!empty($data['create_time'])) unset($data['create_time']);
            if (!empty($data['is_delete'])) unset($data['is_delete']);
            $data['path'] = $this->db()->get($this->_table, 'path', ['unid' => $data['pid']]) . ',' . $data['pid'];
            trim($data['path'], ',');
            
            $result = $this->db()->update($this->_table, $data, ['id' => $id]);
            //task执行强制对应用户下线
        } else {
            $data['unid'] = 'm-' . uniqueId();
            $data['create_time'] = time();
            $data['path'] = $this->db()->get($this->_table, 'path', ['unid' => $data['pid']]) . ',' . $data['pid'];
            trim($data['path'], ',');

            $result = $this->db()->insert($this->_table, $data);
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
        $result = $this->db()->update($this->_table, ['is_delete' => 1], ['id' => $id]);
        if ($result->rowCount() > 0) {
            $ids = $this->db()->select('operate','unid', ['mid' => $id]);
            return $this->model('operate')->delete($ids);
        }
        return false;
    }
}