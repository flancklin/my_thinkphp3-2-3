<?php

namespace Logic\Model\User;


use Logic\Model\BaseModel;
use Think\Model;

class UserAccountModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'user_account';

    const DELETE_VAL = [0, 1];
    const DELETE_YES = 1;//已删除
    const DELETE_NO = 0;//正常

//*********************[开始]前置操作*******************************************************************
//save()->setField()->setInc()/setDec()
    protected function _before_update(&$data, $options) {
        $this->before_insert_and_update($data, $options);
    }

    protected function _before_insert(&$data, $options) {
        $data['create_time'] = time();
        $this->before_insert_and_update($data, $options);
    }

    public function before_insert_and_update(&$data, $option = '', $isInsertMore = false) {
        if ($isInsertMore) {
            $this->error = '用户账户表，禁止批量操作';
            return false;
        } else {
            $data['update_time'] = time();
        }
    }
    //*********************[结束]前置操作*******************************************************************
    //*********************[开始]后置操作*******************************************************************
    protected function _after_insert($data, $options) {
        $this->after_insert_and_update($data, $options);
    }

    protected function _after_update($data, $options) {
        $this->after_insert_and_update($data, $options);
    }

    protected function _after_delete($data, $options) {
        $this->after_insert_and_update($data, $options);
    }

    public function after_insert_and_update($data, $option = '', $isInsertMore = false) {

    }
    //*********************[结束]后置操作*******************************************************************
    protected $_validate = array(
        array('user_no', '18', '用户编码长度为18！！！', Model::MUST_VALIDATE, 'length', Model::MODEL_INSERT),
        //修改时判断uid
        array('uid', self::REG_POS_INTEGER, '用户Id非法！！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        //money=''
        array('money', self::REG_POS_DECIMAL2, '金额非法！！！', Model::EXISTS_VALIDATE, 'regex'),
        array('delete', self::DELETE_VAL, '删除参数非法！！', Model::EXISTS_VALIDATE, 'in'),
    );


    /**
     * 数据库设计
     * CREATE TABLE `t_user_account` (
     * `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
     * `user_no` varchar(18) NOT NULL COMMENT '用户对外编码。对外uid',
     * `money` decimal(8,2) DEFAULT '0.00' COMMENT '用户余额',
     * `withdrawing` decimal(8,2) DEFAULT '0.00' COMMENT '用户提现中冻结资金',
     * `withdraw` decimal(8,2) DEFAULT '0.00' COMMENT '已提现的金额',
     * `delete` tinyint(2) DEFAULT '0' COMMENT '0-未删除，1-已删除',
     * `create_time` int(10) DEFAULT '0',
     * `update_time` int(10) DEFAULT '0',
     * PRIMARY KEY (`uid`)
     * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
     *
     */
}