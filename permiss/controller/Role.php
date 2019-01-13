<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2017/8/27
 * Time: 20:58
 */
namespace permiss\controller;
use permiss\lib\User;

class Role extends User
{
    protected $_roleM;

    protected function afterInit()
    {
        $this->_roleM = $this->model('Job');
    }

    /**
     * @method post
     *
     * @rule form.name|post|角色名称错误 require
     * @rule form.status|post|启用状态错误 integer
     */
    public function saveApi()
    {
        $form = $this->request->post('form');
        $result = $this->_roleM->save($form);
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
        $result = $this->_roleM->getAll();
        return [200, $result];
    }

    /**
     * @method get
     *
     * @rule id|get|角色id错误 integer
     */
    public function getApi($id)
    {
        $result = $this->_roleM->get($id);
        if ($result) {
            return [200, $result];
        }
        return [400, '不存在该职位'];
    }

    /**
     * @method get
     *
     * @rule id|get|角色id错误 integer
     */
    public function deleteApi($id)
    {
        $result = $this->_roleM->delete($id);
        if ($result) {
            return [200, true];
        }
        return [400, '删除失败'];
    }
}