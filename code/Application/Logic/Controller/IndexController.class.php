<?php
namespace Logic\Controller;


use Think\Controller;

class IndexController extends Controller
{

    public function index(){
       var_dump(strtotime(date('Y-m').'-1'));
       var_dump(strtotime(date('Y-m').'-'.date('t').' 23:59:59'));
    }

}