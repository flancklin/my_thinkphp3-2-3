<?php
namespace Extend\Controller;


use Extend\Extend\VerifyCodeExtend;
use Think\Controller;

class VerifyCodeController extends Controller{
    public function index(){
        $handle = new VerifyCodeExtend();
        $handle ->entry();
    }
}
