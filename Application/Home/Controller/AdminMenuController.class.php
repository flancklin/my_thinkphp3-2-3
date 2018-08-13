<?php
namespace Home\Controller;


use Home\Service\AuthMenu;
use Think\Controller;

class AdminMenuController extends Controller
{
    public function index(){
        $menuHandle = new AuthMenu();
        $a = $menuHandle->getMenuTreeData();
        var_dump($a);
    }
}