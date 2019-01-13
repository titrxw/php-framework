<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/9/5
 * Time: 21:05
 */
namespace permiss\model;

use framework\base\Model;

class Job extends Model
{
    protected $_table = 'job';
    protected $_pageSize = 10;

    public function save($data)
    {
      $exists = $this->db()->get($this->_table, 'id', ['name' => $data['name']]);
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
        $data['unid'] = 'j-' . uniqueId();
          $data['create_time'] = time();
        $result = $this->db()->insert($this->_table, $data);
      }

      if ($result->rowCount() > 0) {
        return true;
      }

      return false;
    }

    public function getAll($page = 1)
    {
      $total = $this->db()->count($this->_table, ['is_delete' => 0]);
      if (!$total) {
        return ['total'=>0, 'data'=>[]];
      }
      $data = $this->db()->select($this->_table, ['id', 'name', 'status'], ['is_delete' => 0,'LIMIT' => [($page - 1) * $this->_pageSize, $this->_pageSize]]);
      return ['total'=>$total, 'data'=>$data];
    }

    public function get($id) 
    {
        return $this->db()->get($this->_table, ['id', 'name', 'status'], ['id' => $id, 'is_delete' => 0]);
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