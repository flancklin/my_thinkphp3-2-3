<?php

namespace Logic\Model\User;


use Logic\Model\BaseModel;
use Think\Model;

class UserInfoModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'user_info';

    const SEX_VAL = [0, 1, 2, 3];
    const SEX_BOY = 1;//男性
    const SEX_GIRL = 2;//女性
    const SEX_OTHER = 3;//已确认是未知性别
    const SEX_DEFAULT = 0;//未填写

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
            $this->error = '用户信息不允许批量添加';
            return false;
        } else {
            if (C('encode_Tel_in_db') && isset($data['mobile']))
                $data['mobile'] = encodeTel($data['mobile']);
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
        //任何情况下都必须验证
        array('uid', self::REG_POS_INTEGER, '缺少用户信息！！！', Model::MUST_VALIDATE,'regex', Model::MODEL_UPDATE),
        //新添加时必须验证
        array('sex', self::SEX_VAL, '性别非法！', Model::EXISTS_VALIDATE, 'in'),
        array('nickname', '0,50', '昵称最长50！', Model::EXISTS_VALIDATE, 'length'),
        array('headimgurl', '0,200', '头像链接最长200！', Model::EXISTS_VALIDATE, 'length'),
        array('mobile', BaseModel::REG_MOBILE, '手机号格式非法！', Model::EXISTS_VALIDATE)
    );


    /**
     * 数据库设计
     * CREATE TABLE `t_user_info` (
     * `uid` int(11) unsigned NOT NULL,
     * `sex` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0-未知，1-男，2-女，3-其他',
     * `nickname` varchar(50) DEFAULT NULL,
     * `headimgurl` varchar(200) DEFAULT '',
     * `mobile` varchar(15) DEFAULT '' COMMENT '用户手机号',
     * `country` varchar(50) DEFAULT '' COMMENT '国家',
     * `province` varchar(50) DEFAULT '' COMMENT '省',
     * `city` varchar(50) DEFAULT '' COMMENT '市',
     * `county` varchar(50) DEFAULT '' COMMENT '县',
     * `create_time` int(11) DEFAULT '0',
     * `update_time` int(11) DEFAULT '0',
     * PRIMARY KEY (`uid`)
     * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
     *
     */
}