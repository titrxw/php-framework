<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/5
 * Time: 21:05
 */
namespace permiss\model;

use framework\base\Model;

class Depart extends Model
{
  protected $_table = 'department';

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

        $result = $this->db()->update($this->_table, $data, ['id' => $id]);
      } else {
        $data['unid'] = 'd-' . uniqueId();
        $data['create_time'] = time();
        $result = $this->db()->insert($this->_table, $data);
      }

      if ($result->rowCount() > 0) {
        return true;
      }

      return false;
    }

    public function getAll()
    {
      return $this->db()->select($this->_table, ['alias', 'id', 'name(title)', 'pid', 'status', 'unid'], ['is_delete' => 0]);
    }

    public function get($id) 
    {
        return $this->db()->get($this->_table, '*', ['id' => $id, 'is_delete' => 0]);
    }

    public function delete($id) 
    {
        $result = $this->db()->update($this->_table, ['is_delete' => 1], ['id' => $id]);
        if ($result->rowCount() > 0) {
          return true;
        }
        return false;
    }
}