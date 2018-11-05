<?php

namespace Logic\Model;

use Think\Model;

class BaseModel extends Model {
    //test_test和???表示测试和有问题的地方
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

    const REG_TEL_MOBILE = '/^(0\d{2,3}-\d{7,8})|(1[34589]\d{9})$/';//手机支持13，14，15，18号段得
    const REG_TEL = '/^0\d{2,3}-\d{7,8}$/';//座机号
    const REG_MOBILE = '/^1[34589]\d{9}$/';//手机号
    const REG_NUMBER = '/^\d+$/';                   //自然数，0123456789
    const REG_POS_INTEGER = '/^[1-9](\d*)?$/';           //自然数，123456789
    const REG_INTEGER = '/^[-\+]?\d+$/';             //整数（正整数+负整数+0）
    const REG_DECIMAL = '/^[-\+]?\d+(\.\d+)?$/';      //正负浮点数
    const REG_DECIMAL2 = '/^[-\+]?\d+(\.\d{1,2})?$/'; //正负浮点数（最多两位小数得）
    const REG_POS_DECIMAL = '/^\d+(\.\d+)?$/';            //正浮点数
    const REG_POS_DECIMAL2 = '/^\d+(\.\d{1,2})?$/';        //正浮点数（最多两位小数


    public function before_insert_and_update(&$data, $options = '', $isInsertMore = false) {
    }

    public function after_insert_and_update($data, $options = '', $isInsertMore = false) {
    }
}