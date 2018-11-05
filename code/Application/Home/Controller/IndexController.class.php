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
        var_dump(array_merge(['a'=>1,'b'=>3],['a'=>4,'c'=>'9']));
    }
}