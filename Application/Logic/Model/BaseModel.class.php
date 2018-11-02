<?php

namespace Logic\Model;

use Think\Model;

class BaseModel extends Model {

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