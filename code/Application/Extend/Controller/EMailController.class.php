<?php
namespace Extend\Controller;


use Extend\Extend\EMailExtend;
use Think\Controller;

class EMailController extends Controller
{
    public function index(){
//        echo phpinfo();die;
        $handle = new EMailExtend();
        $a = $handle -> send('1026544401@qq.com','nihao'.rand(0,99999999999999999999),'hello world');
        var_dump($a);
        var_dump($handle->ErrorInfo);
    }
}