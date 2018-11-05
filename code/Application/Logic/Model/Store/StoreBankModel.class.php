<?php

namespace Logic\Store\Model;


use Logic\Model\BaseModel;
use Think\Model;

class StoreBankModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'store_bank';

    const TYPE_VAL = [0, 1];
    const BANK_TYPE_BANK = 0;//银行卡
    const BANK_TYPE_ALI = 1;//支付宝

    const DELETE_VAL = [0, 1];
    const DELETE_YES = 1;//已删除
    const DELETE_NO = 0;//正常

    const DEFAULT_VAL = [0, 1];
    const DEFAULT_YES = 1;//地址是默认首选地址
    const DEFAULT_NO = 0;//地址不是默认首选地址

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
            foreach ($data as $d) {
                if (C('encode_Tel_in_db') && isset($d['mobile']))
                    $d['mobile'] = encodeTel($d['mobile']);
                if (C('encode_name_in_db') && isset($d['card_name']))
                    $d['card_name'] = encodeName($d['card_name']);
                if (C('encode_no_in_db') && isset($d['card_number']))
                    $d['card_number'] = encodeNo($d['card_number']);
                $d['update_time'] = time();
            }
        } else {
            if (C('encode_Tel_in_db') && isset($data['mobile']))
                $data['mobile'] = encodeTel($data['mobile']);
            if (C('encode_name_in_db') && isset($data['card_name']))
                $data['card_name'] = encodeName($data['card_name']);
            if (C('encode_no_in_db') && isset($data['card_number']))
                $data['card_number'] = encodeNo($data['card_number']);
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
        array('uid', self::REG_POS_INTEGER, '缺少用户信息！！！', Model::MUST_VALIDATE),
        //全部都存在就验证。不想太麻烦了
        array('bank_type', self::TYPE_VAL, '账户类型非法！', Model::EXISTS_VALIDATE, 'in'),
        array('bank', '0,20', '银行名称最长20', Model::EXISTS_VALIDATE, 'length'),
        array('open_bank', '0,120', '开户行名称最长120', Model::EXISTS_VALIDATE, 'length'),
        array('number', self::REG_MOBILE, '银行预留手机号格式不对', Model::EXISTS_VALIDATE),
        array('number', '0,50', '账号最长50', Model::EXISTS_VALIDATE, 'length'),
        array('card_name', '0,50', '证件姓名最长50', Model::EXISTS_VALIDATE, 'length'),
        array('card_number', '0,50', '证件号码最长50', Model::EXISTS_VALIDATE, 'length'),
        array('default', self::DEFAULT_VAL, '默认参数非法！', Model::EXISTS_VALIDATE, 'in'),
        array('delete', self::DELETE_VAL, '删除参数非法！', Model::EXISTS_VALIDATE, 'in'),
    );


    /**
     *数据库设计
     * CREATE TABLE `t_bank_card` (
     * `bank_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
     * `uid` int(11) NOT NULL COMMENT 'user_type=0表示用户的ID，为1表示店铺的ID',
     * `type` tinyint(2) NOT NULL COMMENT '0-银行卡，1-支付宝，2-微信？？',
     * `bank` varchar(20) DEFAULT '' COMMENT '银行卡名称',
     * `open_bank` varchar(120) DEFAULT '' COMMENT '开户行地址',
     * `mobile`  varchar(12) NULL DEFAULT '' COMMENT '银行预留手机号'，
     * `number` varchar(50) DEFAULT NULL COMMENT '银行卡账号，支付宝账号',
     * `card_name` varchar(50) DEFAULT '' COMMENT '身份证姓名',
     * `card_number` varchar(20) DEFAULT '' COMMENT '省份证号码',
     * `default`  tinyint(2) NULL DEFAULT 0 COMMENT '0-非默认。1-默认',
     * `delete`  tinyint(2) NULL DEFAULT 0 COMMENT '0-未删除，1-已删除',
     * `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
     * `update_time` int(10) DEFAULT NULL COMMENT '修改时间',
     * PRIMARY KEY (`bank_id`)
     * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
     *
     */
}