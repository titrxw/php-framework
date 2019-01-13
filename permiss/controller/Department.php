<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace permiss\controller;
use permiss\lib\User;

class Department extends User
{
    protected $_departM;

    protected function afterInit()
    {
        $this->_departM = $this->model('Depart');
    }

    /**
     * @method post
     * 
     * @rule form.name|post|部门名称错误 require
     * @rule form.pid|post|上级部门错误 require
     * @rule form.status|post|启用状态错误 integer
     */
    public function saveApi()
    {
        $form = $this->request->post('form');
        $result = $this->_departM->save($form);
        if ($result) {
            return [200, true];
        }
        return [501, '操作失败'];
    }

    /**
     * @method get
     * 
     */
    public function listApi()
    {
        $result = $this->_departM->getAll();
        return [200, $this->tree->get($result)];
    }

    /**
     * @method get
     * 
     * @rule id|get|部门id错误 integer
     */
    public function getApi($id)
    {
        $result = $this->_departM->get($id);
        if ($result) {
            return [200, $result];
        }
        return [400, '不存在该部门'];
    }

    /**
     * @method get
     * 
     * @rule id|get|部门id错误 integer
     */
    public function deleteApi($id)
    {
        $result = $this->_departM->delete($id);
        if ($result) {
            return [200, true];
        }
        return [400, '删除失败'];
    }
}