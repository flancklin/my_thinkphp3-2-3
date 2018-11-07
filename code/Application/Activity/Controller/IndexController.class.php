<?php
namespace Activity\Controller;

use Think\Controller;

class IndexController extends  Controller{
    public function zp(){
        $this->display();
    }

    public function backZp(){

        $this->ajaxReturn(array('status'=>true,'msg'=>'成功了','jxnum'=>2));
    }
}