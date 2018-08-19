<?php
namespace Logic\Controller;

use Logic\Logic\UserAddressLogic;
use Logic\Model\AddressModel;
use Logic\Model\AuthMenuModel;
use Think\Controller;

class UserAddressController extends Controller{
    public function add(){
        $data = [];
        $data['uid']          = 2;//300139;
        $data['username']     = '张三';
        $data['country_id']   = 1;
        $data['province_id']  = 2;
        $data['city_id']      = 3;
        $data['county_id']    = 4;
        $data['town_id']      = 5;
        $data['address_desc'] = '你好';
        $handle = new UserAddressLogic($data['uid']);
        var_dump($handle->selectData(['address_id'=> ['gt', 0]]));
        var_dump($handle->errMes);
    }
}