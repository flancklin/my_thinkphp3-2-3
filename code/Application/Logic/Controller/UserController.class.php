<?php
namespace Logic\Controller;

use Logic\Logic\UserLogic;
use Think\Controller;

class UserController extends Controller{
    public function add(){
        var_dump(111);
        $logic = new UserLogic(2);
        $a = $logic->readMore('user_address');
        var_dump($a);
    }
}