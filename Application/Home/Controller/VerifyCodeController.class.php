<?php
namespace Home\Controller;


use Home\Service\VerifyCodeService;
use Think\Controller;

class VerifyCodeController extends Controller{
    public function index(){
        $handle = new VerifyCodeService();
        $handle ->entry();
    }
}
