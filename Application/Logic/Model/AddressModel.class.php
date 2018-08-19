<?php
namespace Logic\Model;


use Think\Model;

class AddressModel extends Model{
    protected $tablePrefix = 't_';
    protected $tableName   = 'address';

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
        $data = $this->_before_insert_and_update_data($data);
    }
    protected function _before_insert(&$data, $options){
        $data['create_time'] = time();
        $data = $this->_before_insert_and_update_data($data);
    }
    private function _before_insert_and_update_data($data){
        if(isset($data['address_type']) && !in_array($data['address_type'], [self::ADDRESS_TYPE_OTHER, self::ADDRESS_TYPE_HOME, self::ADDRESS_TYPE_COMPANY]))
            $data['address_type'] = self::ADDRESS_TYPE_OTHER;
        if(isset($data['address_default']) && !in_array($data['address_default'], [self::ADDRESS_DEFAULT_NO, self::ADDRESS_DEFAULT_YES]))
            $data['address_default'] = self::ADDRESS_DEFAULT_NO;
        if(isset($data['address_status']) && !in_array($data['_before_insert'], [self::ADDRESS_STATUS_DEL, self::ADDRESS_STATUS_OK]))
            $data['address_status'] = self::ADDRESS_STATUS_OK;
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


    protected $_validate = array(
        array('uid','/^[1-9](\d*)?$/','缺少用户信息',self::MUST_VALIDATE),
        array('username','require','收件人必须填写！',self::MUST_VALIDATE),  //任何时候都必须验证
        array('user_tel','/^(0\d{2,3}-\d{7,8})|(1[3458]\d{9})$/','收件人电话号码必须填写！', self::MUST_VALIDATE),
        array('province_id','/^[1-9](\d*)?$/','必须选择省份！',self::MUST_VALIDATE),
        array('city_id','/^[1-9](\d*)?$/','必须选择市',self::MUST_VALIDATE),
        array('county_id','/^[1-9](\d*)?$/','必须选择区县',self::MUST_VALIDATE),
//        array('town_id','require','必须选择乡镇'),
        array('address_desc','require','必须选择地址描述',self::MUST_VALIDATE),
    );
}