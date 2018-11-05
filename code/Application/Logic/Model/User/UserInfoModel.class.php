<?php
namespace Logic\Model\User;


use Logic\Model\BaseModel;
use Think\Model;

class UserInfoModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'user_address';


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
                if (C('encode_Tel_in_db') && isset($d['user_tel']))
                    $d['user_tel'] = encodeTel($d['user_tel']);
                $d['update_time'] = time();
            }
        } else {
            if (C('encode_Tel_in_db') && isset($data['user_tel']))
                $data['user_tel'] = encodeTel($data['user_tel']);
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
        //新添加时必须验证
        array('username', 'require', '收件人填写有误！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('user_tel', self::REG_TEL_MOBILE, '电话号码填写有误！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('province_id', self::REG_POS_INTEGER, '省份参数非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('city_id', self::REG_POS_INTEGER, '市级参数非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('county_id', self::REG_POS_INTEGER, '区县参数非法！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        array('desc', 'require', '地址描述不能为空！！', Model::MUST_VALIDATE, 'regex', Model::MODEL_INSERT),
        //在修改的时候存在就验证，不存在就算了
        array('address_id', self::REG_POS_INTEGER, '地址核心信息非法！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('username', 'require', '收件人填写有误！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('user_tel', self::REG_TEL_MOBILE, '电话号码填写有误！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('province_id', self::REG_POS_INTEGER, '省份参数非法！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('city_id', self::REG_POS_INTEGER, '市级参数非法！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('county_id', self::REG_POS_INTEGER, '区县参数非法！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        array('desc', 'require', '地址描述不能为空！', Model::EXISTS_VALIDATE, 'regex', Model::MODEL_UPDATE),
        //新增或者修改的时候，存在就验证，不存在就算了
        array('town_id', self::REG_POS_INTEGER, '乡镇参数非法！', Model::EXISTS_VALIDATE),//在添加或者更新时存在字段就验证
        array('type', self::ADDRESS_TYPE, '地址类型非法！', Model::EXISTS_VALIDATE, 'in'),
        array('default', self::ADDRESS_DEFAULT, '默认地址值非法！', Model::EXISTS_VALIDATE, 'in'),
        array('delete', self::ADDRESS_DELETE, '地址状态非法！', Model::EXISTS_VALIDATE, 'in')
    );


    /**
     * 数据库设计
     *
     * DROP TABLE IF EXISTS `t_user_address`;
     * CREATE TABLE `t_user_address` (
     * `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户的地址的唯一标识',
     * `uid` int(11) DEFAULT NULL COMMENT '用户ID',
     * `username` varchar(20) DEFAULT NULL COMMENT '地址-收件人',
     * `user_tel` varchar(32) DEFAULT NULL COMMENT '地址-收件人电话（手机+座机）',
     * `country_id` int(6) NOT NULL DEFAULT '1' COMMENT '地址-国家.默认1-中国',
     * `province_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-省份',
     * `city_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-市',
     * `county_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-县郡',
     * `town_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-乡镇',
     * `village_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-小区或者村或者街道',
     * `desc` varchar(50) NOT NULL DEFAULT '' COMMENT '地址-门牌之类的描述',
     * `type` tinyint(2) DEFAULT '0' COMMENT '地址的类型。0-未选择，1-家庭，2-公司',
     * `default` tinyint(2) DEFAULT '0' COMMENT '是否是默认地址。0-不是，1-默认地址',
     * `delete` tinyint(2) DEFAULT '1' COMMENT '地址状态。0-删除，1-正常使用',
     * `create_time` int(10) DEFAULT NULL COMMENT '创建这条记录的时间',
     * `update_time` int(10) DEFAULT '0' COMMENT '修改地址的最新时间',
     * PRIMARY KEY (`address_id`)
     * ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
     *
     */
}