<?php

namespace Home\Controller;


use Think\Controller;

/**
 *
 * 后台管理界面校色不同展示栏目不同
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends Controller {

const a= [33,434];
    public function index() {
        $this->display();
    }
    public function sql(){
        $this->display();
    }
    public function php(){
        $this->display();
    }
    public function tp(){
        $this->display();
    }

    public function a(){
        $this->ajaxReturn([I("param.")]);
    }
}
