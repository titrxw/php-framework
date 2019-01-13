<?php
/**
 * Created by PhpStorm.
 * User: rxw
 * Date: 2019/1/9
 * Time: 20:45
 */
namespace permiss\controller;

use permiss\lib\User;

class Operate extends User
{
    protected $_operateM;

    protected function afterInit()
    {
        $this->_operateM = $this->model('Operate');
    }

    /**
     * @method post
     *
     * @rule form.name|post|功能名称错误 require
     * @rule form.url|post|链接错误 require
     * @rule form.mid|post|所属模块错误 require
     * @rule form.status|post|启用状态错误 integer
     */
    public function saveApi()
    {
        $form = $this->request->post('form');
        $result = $this->_operateM->save($form);
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
        $result = $this->_operateM->getAll();
        return [200, $result];
    }

    /**
     * @method get
     *
     * @rule id|get|功能id错误 integer
     */
    public function getApi($id)
    {
        $result = $this->_operateM->get($id);
        if ($result) {
            return [200, $result];
        }
        return [400, '不存在该功能'];
    }

    /**
     * @method get
     *
     * @rule id|get|模块id错误 integer
     */
    public function deleteApi($id)
    {
        $result = $this->_operateM->delete($id);
        if ($result) {
            return [200, true];
        }
        return [400, '删除失败'];
    }
}