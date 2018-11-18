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
    public function index() {
        $this->display();
    }
    public function t(){
        $string = "This is\tan example\nstring";
        /* 使用制表符和换行符作为分界符 */
        $tok = strtok($string, " \n\t");

        while ($tok !== false) {
            echo "Word=$tok<br />";
            $tok = strtok(" \n\t");
        }
        echo $string;
    }
}
