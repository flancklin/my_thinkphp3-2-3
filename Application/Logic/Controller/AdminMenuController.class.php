<?php
namespace Logic\Controller;


use Logic\Logic\AuthMenuLogic;
use Think\Controller;

class AdminMenuController extends Controller
{
    public function index(){
        $menuHandle = new AuthMenuLogic();
        $a = $menuHandle->getMenuTreeData();
        var_dump($a);
    }
}