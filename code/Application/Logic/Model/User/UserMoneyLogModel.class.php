<?php
namespace Logic\Model\User;


use Logic\Model\BaseModel;
use Think\Model;

class UserMoneyLogModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'user_money_log';

    const TYPE_VAL = [9,10];//发生资金记录类型
    const TYPE_WITHDRAW = 9;//发生提现
    const TYpe_TEST = 10;//入账资金//test_test

    const STATUS_VAL = [0, 1, 2];
    const STATUS_WAIT = 2;//记录待处理中
    const STATUS_OK = 1;//记录处理成功
    const STATUS_FAIL = 0;//记录处理为失败


//*********************[开始]前置操作*******************************************************************
//save()->setField()->setInc()/setDec()
    protected function _before_insert(&$data, $options) {
        $data['create_time'] = time();
        $this->before_insert_and_update($data, $options);
    }
    protected function _before_update(&$data, $options) {
        $this->before_insert_and_update($data, $options);
    }

    public function before_insert_and_update(&$data, $option = '', $isInsertMore = false) {
        if ($isInsertMore) {
            foreach ($data as $d) {
                $d['update_time'] = time();
            }
        } else {
            $data['update_time'] = time();
        }
    }

    //*********************[结束]后置操作*******************************************************************


    protected $_validate = array(
        //任何情况下都必须验证
        array('uid', self::REG_POS_INTEGER, '缺少用户信息！！！', Model::MUST_VALIDATE),
        //新添加时必须验证
        array('type', self::TYPE_VAL, '交易类型非法！！', Model::MUST_VALIDATE, 'in', Model::MODEL_INSERT),
        array('before_money', self::REG_POS_DECIMAL2, '原账户金额值非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('happen_money', self::REG_POS_DECIMAL2, '交易金额值非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('status', [self::STATUS_WAIT, self::STATUS_OK], '记录状态值非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),

        array('status', [self::STATUS_FAIL, self::STATUS_OK], '记录状态值非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('desc', '0,100', '交易说明最长100！！', Model::EXISTS_VALIDATE, 'length'),
    );


    /**
     * 数据库设计
     * CREATE TABLE `t_user_money_log` (
     * `id` int(11) NOT NULL,
     * `uid` int(11) NOT NULL,
     * `type` tinyint(2) NOT NULL COMMENT '0-9出账户，10-99入账户。视情况定义',
     * `before_money` decimal(8,2) NOT NULL,
     * `happen_money` decimal(8,2) NOT NULL,
     * `desc` varchar(100) DEFAULT NULL,
     * `status` tinyint(2) DEFAULT '2' COMMENT '2-待处理，1-处理为成功，0-处理为失败',
     * `create_time` int(11) DEFAULT NULL,
     * PRIMARY KEY (`id`)
     * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
     *
     */
}