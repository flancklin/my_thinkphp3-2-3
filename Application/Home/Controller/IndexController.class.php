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
	
	
	
	
	public function index(){
        $oldRecord['beginUseTime'] = 5600;//3600+1500
        $oldRecord['endUseTime']   = 14640;
        if($oldRecord['beginUseTime']){
            var_dump(intval($oldRecord['beginUseTime'] / 3600));
            var_dump($oldRecord['beginUseTime'] % 3600 / 60);
            $oldRecord['beginUseTime'] = sprintf('%02d:%02d:00',intval($oldRecord['beginUseTime'] / 3600), $oldRecord['beginUseTime'] % 3600 / 60);
        }else{
            $oldRecord['beginUseTime'] = '00:00:00';
        }
        if($oldRecord['endUseTime']){

            var_dump($oldRecord['endUseTime'] / 3600);
            var_dump($oldRecord['endUseTime'] / 60);
            $oldRecord['endUseTime'] = sprintf('%02d:%02d:00',$oldRecord['endUseTime'] / 3600, $oldRecord['endUseTime'] % 3600 / 60);
        }else{
            $oldRecord['endUseTime'] = '00:00:00';
        }
        var_dump($oldRecord);
	}
}