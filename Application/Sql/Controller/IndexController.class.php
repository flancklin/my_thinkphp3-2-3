<?php
namespace Sql\Controller;


use Think\Controller;

class IndexController extends Controller{

    public function index(){
        $sql = "按特定数组排序：SELECT `id`,`operatorsId`,`store_city_id`,`storeId`,`proIds` FROM `tp_team_buy_product` WHERE `isDelete` = 'false' AND `id` IN ('500035','500011','500027')  order by field(id,'500035','500011','500027')";
        $sql .= "<br />";

        echo $sql;
    }
}