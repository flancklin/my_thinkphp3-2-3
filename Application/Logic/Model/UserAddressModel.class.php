<?php
namespace Logic\Model;


use Think\Model;

class UserAddressModel extends Model{
    protected $tablePrefix = 't_';
    protected $tableName   = 'user_address';

    private $encodeTelInDb = true;     //手机号在数据库中是否加密存储了
    private $encodeTelSalt = 'my_salt';//加密撒盐码

    const ADDRESS_TYPE_COMPANY = 2;//公司地址
    const ADDRESS_TYPE_HOME    = 1;//家庭地址
    const ADDRESS_TYPE_OTHER   = 0;//未知地址

    const ADDRESS_STATUS_OK    = 1;//地址在正常使用
    const ADDRESS_STATUS_DEL   = 0;//地址已经删除作废

    const ADDRESS_DEFAULT_YES  = 1;//地址是默认首选地址
    const ADDRESS_DEFAULT_NO   = 0;//地址不是默认首选地址

//*********************前置操作*******************************************************************
    protected function _before_update(&$data, $options){
        $this->_before_insert_and_update_data($data);
    }
    protected function _before_insert(&$data, $options){
        $data['create_time'] = time();
         $this->_before_insert_and_update_data($data);
    }
    private function _before_insert_and_update_data(&$data){
        if($this->encodeTelInDb && isset($data['user_tel']))
            $data['user_tel'] = encodeTel($data['user_tel'], $this->encodeTelSalt);
        $data['update_time'] = time();
    }
/*//这样处是错误得。result是一个多维数组
//*********************后置操作*******************************************************************
    protected function _after_select(&$result,$options){
        $result = $this->_after_select_deal_data($result);//这样处是错误得。result是一个多维数组
    }
    protected function _after_find(&$result,$options) {
        $result = $this->_after_select_deal_data($result);
    }
    private function _after_select_deal_data(&$result){
       $this->encodeTelInDb && $result['user_tel'] = decodeTel($result['user_tel'], $this->encodeTelSalt);
    }*/

    /**
     * array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
     * 验证字段:数据库中的字段
     * 验证规则:大于0还是小于0还是不为空
     * 错误提示:
     * 验证条件:
                self::EXISTS_VALIDATE 或者0 存在字段就验证（默认）//data中有就验证没得不管
                self::MUST_VALIDATE 或者1 必须验证               //data中有没得都验证，没得就是错误
                self::VALUE_VALIDATE或者2 值不为空的时候验证      //data中即使有这个字段，但是为空时，我也不验证
     * 附加规则:默认regex(正则表达式)
                regex     :正则表达式
                function  :函数验证(自定义或者系统的都可以) array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
                callback  :
                confirm   :判断是否和另一个字段值一样       array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
                in        :判断值是否在某个区间             array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
                equal     :
                notequal  :
                length    :验证长度，定义的验证规则可以是一个数字（表示固定长度）或者数字范围（例如3,12 表示长度从3到12的范围）
                between   :验证范围，定义的验证规则表示范围，可以使用字符串或者数组，例如1,31或者array(1,31)
                notbetween:
                expire    :验证是否在有效期，定义的验证规则表示时间范围，可以到时间，例如可以使用 2012-1-15,2013-1-15 表示当前提交有效期在2012-1-15到2013-1-15之间，也可以使用时间戳定义
                ip_allow  :验证IP是否允许，定义的验证规则表示允许的IP地址列表，用逗号分隔，例如201.12.2.5,201.12.2.6
                ip_deny   :
                unique    : 验证是否唯一，系统会根据字段目前的值查询数据库来判断是否存在相同的值，当表单数据中包含主键字段时unique不可用于判断主键字段本身    array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
     * 验证时间:
                self::MODEL_INSERT或者1新增数据时候验证
                self::MODEL_UPDATE或者2编辑数据时候验证
                self::MODEL_BOTH或者3全部情况下验证（默认）
                这里的验证时间需要注意，并非只有这三种情况，你可以根据业务需要增加其他的验证时间。
     */
    protected $_validate = array(
      //因为地址信息修改和新添加的时候，数据都是一样的，所以【验证时间】采用了3，修改和添加的时候都验证
        array('uid','/^[1-9](\d*)?$/','缺少用户信息',self::MUST_VALIDATE),
        array('username','require','收件人填写有误！',self::MUST_VALIDATE),  //任何时候都必须验证
        array('user_tel','/^(0\d{2,3}-\d{7,8})|(1[3458]\d{9})$/','电话号码填写有误！', self::MUST_VALIDATE),
        array('province_id','/^[1-9](\d*)?$/','必须选择省份！',self::MUST_VALIDATE),//必须大于0
        array('city_id','/^[1-9](\d*)?$/','必须选择市',self::MUST_VALIDATE),
        array('county_id','/^[1-9](\d*)?$/','必须选择区县',self::MUST_VALIDATE),
//        array('town_id','require','必须选择乡镇'),
        array('address_desc','require','必须选择地址描述',self::MUST_VALIDATE),
        array('address_type', array(0,1,2), '地址类型错误', self::EXISTS_VALIDATE, 'in'),//存在字段就验证
        array('address_default', array(0,1), '默认地址值错误', self::EXISTS_VALIDATE, 'in'),
        array('address_status', array(0,1), '地址状态错误', self::EXISTS_VALIDATE, 'in')
    );


    /**
     * 数据库设计
     *
    DROP TABLE IF EXISTS `t_user_address`;
    CREATE TABLE `t_user_address` (
    `address_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户的地址的唯一标识',
    `uid` int(11) DEFAULT NULL COMMENT '用户ID',
    `username` varchar(20) DEFAULT NULL COMMENT '地址-收件人',
    `user_tel` varchar(32) DEFAULT NULL COMMENT '地址-收件人电话（手机+座机）',
    `country_id` int(6) NOT NULL DEFAULT '1' COMMENT '地址-国家.默认1-中国',
    `province_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-省份',
    `city_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-市',
    `county_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-县郡',
    `town_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-乡镇',
    `village_id` int(6) NOT NULL DEFAULT '0' COMMENT '地址-小区或者村或者街道',
    `address_desc` varchar(50) NOT NULL DEFAULT '' COMMENT '地址-门牌之类的描述',
    `address_type` tinyint(2) DEFAULT '0' COMMENT '地址的类型。0-未选择，1-家庭，2-公司',
    `address_default` tinyint(2) DEFAULT '0' COMMENT '是否是默认地址。0-不是，1-默认地址',
    `address_status` tinyint(2) DEFAULT '1' COMMENT '地址状态。0-删除，1-正常使用',
    `create_time` int(10) DEFAULT NULL COMMENT '创建这条记录的时间',
    `update_time` int(10) DEFAULT '0' COMMENT '修改地址的最新时间',
    PRIMARY KEY (`address_id`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
     *
     */
}