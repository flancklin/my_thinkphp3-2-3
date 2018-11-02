<?php
namespace Logic\Controller;

use Logic\Logic\UserAddressLogic;
use Logic\Model\UserAddressModel;
use Think\Controller;

class UserAddressController extends Controller{
    public function add(){
//        $d = [];
//        $data = [];
//        $data['uid']          = 2;//300139;
//        $data['username']     = '张三';
//        $data['user_tel']     = '18236956986';
//        $data['country_id']   = 1;
//        $data['province_id']  = 2;
//        $data['city_id']      = 3;
//        $data['county_id']    = 4;
//        $data['town_id']      = 5;
//        $data['desc'] = '你好';
//        $d[]=$data;
//        $data = [];
//        $data['uid']          = 2;//300139;
//        $data['username']     = '张三';
//        $data['user_tel']     = '18236956986';
//        $data['country_id']   = 1;
//        $data['province_id']  = 2;
//        $data['city_id']      = 3;
//        $data['county_id']    = 4;
//        $data['town_id']      = 5;
//        $data['desc'] = '你好';
//        $d[]=$data;
        $data = [];
        $data['uid']          = 2;//300139;
        $data['username']     = '张三fdsfdsfd';
        $data['user_tel']     = '18236956986';
        $data['country_id']   = 1;
        $data['province_id']  = 2;
        $data['city_id']      = 3;
        $data['county_id']    = 4;
        $data['town_id']      = 5;
        $data['default']       = UserAddressModel::ADDRESS_DEFAULT_YES;
        $data['desc'] = '你好ffsfd';
        $d[]=$data;
        $handle = new UserAddressLogic(1,6);

        var_dump($handle->createOne($data));
//        var_dump($handle->update(['default'=>UserAddressModel::ADDRESS_DEFAULT_YES]));
//        var_dump($handle->delete());
//        var_dump($handle->showErrMes);
//        var_dump($handle->errSql);
//        var_dump($handle->code);
    }
}