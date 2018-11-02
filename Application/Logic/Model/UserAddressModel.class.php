<?php

namespace Logic\Model;


use Think\Model;

class UserAddressModel extends BaseModel {
    protected $tablePrefix = 't_';
    protected $tableName = 'user_address';

    const ADDRESS_TYPE = [0, 1, 2];
    const ADDRESS_TYPE_COMPANY = 2;//公司地址
    const ADDRESS_TYPE_HOME = 1;//家庭地址
    const ADDRESS_TYPE_OTHER = 0;//未知地址

    const ADDRESS_DELETE = [0, 1];
    const ADDRESS_DELETE_YES = 1;//已删除
    const ADDRESS_DELETE_NO = 0;//正常

    const ADDRESS_DEFAULT = [0, 1];
    const ADDRESS_DEFAULT_YES = 1;//地址是默认首选地址
    const ADDRESS_DEFAULT_NO = 0;//地址不是默认首选地址

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

    /**
     * array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
     * 验证字段:数据库中的字段
     * 验证规则:大于0还是小于0还是不为空
     * 错误提示:
     * 验证条件:
     * self::EXISTS_VALIDATE 或者0 存在字段就验证（默认）//data中有就验证没得不管
     * self::MUST_VALIDATE 或者1 必须验证               //data中有没得都验证，没得就是错误
     * self::VALUE_VALIDATE或者2 值不为空的时候验证      //data中即使有这个字段，但是为空时，我也不验证
     * 附加规则:默认regex(正则表达式)
     * regex     :正则表达式
     * function  :函数验证(自定义或者系统的都可以) array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
     * callback  :
     * confirm   :判断是否和另一个字段值一样       array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
     * in        :判断值是否在某个区间             array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
     * equal     :
     * notequal  :
     * length    :验证长度，定义的验证规则可以是一个数字（表示固定长度）或者数字范围（例如3,12 表示长度从3到12的范围）
     * between   :验证范围，定义的验证规则表示范围，可以使用字符串或者数组，例如1,31或者array(1,31)
     * notbetween:
     * expire    :验证是否在有效期，定义的验证规则表示时间范围，可以到时间，例如可以使用 2012-1-15,2013-1-15 表示当前提交有效期在2012-1-15到2013-1-15之间，也可以使用时间戳定义
     * ip_allow  :验证IP是否允许，定义的验证规则表示允许的IP地址列表，用逗号分隔，例如201.12.2.5,201.12.2.6
     * ip_deny   :
     * unique    : 验证是否唯一，系统会根据字段目前的值查询数据库来判断是否存在相同的值，当表单数据中包含主键字段时unique不可用于判断主键字段本身    array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
     * 验证时间:
     * self::MODEL_INSERT或者1新增数据时候验证
     * self::MODEL_UPDATE或者2编辑数据时候验证
     * self::MODEL_BOTH或者3全部情况下验证（默认）
     * 这里的验证时间需要注意，并非只有这三种情况，你可以根据业务需要增加其他的验证时间。
     */
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