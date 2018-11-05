<?php
namespace Logic\Logic;


use Logic\Model\AuthMenuModel;

class AuthMenuLogic extends Logic {
    const IS_DOCUMENT_YES  = 1;
    const IS_DOCUMENT_NO   = 0;

    const IS_DELETE_YES    = 1;
    const IS_DELETE_NO     = 0;


    /**
     * 获取整个目录
     * @return array
     */
    public function getMenuTreeData(){
        return  self::tree(( new AuthMenuModel()) -> select());
    }

    public function addMenuTreeBranch($params){
        if(!isset($params['parent_menu_id']) || !isset($params['title']) || !isset($params['is_document'])){
            $this-> errMes = '添加失败，缺少必要参数';
            return false;
        }
        //检查 传进来的参数的有效性
        $handleModelMenu =  new AuthMenuModel();
        //如果是添加链接
        if($params['is_document'] == self::IS_DOCUMENT_NO){
            //检查   parent_menu_id>0 ;title不能为空；link不能为空
            $parentWhere = [];
            $parentWhere['menu_id']     = $params['parent_menu_id'];
            $parentWhere['is_delete']   = self::IS_DELETE_NO;//可设置前置操作
            $parentWhere['is_document'] = self::IS_DOCUMENT_YES;
            $parentMenu = $handleModelMenu
                -> where($parentWhere)
                -> find();
            if(empty($parentMenu)){
                $this -> errMes = '上级目录不存在或不是目录';
                $this -> errSql = $handleModelMenu ->getLastSql();
                return false;
            }
            $params['root_menu_id'] = $parentMenu['root_menu_id'];
            if($handleModelMenu -> add($params)){
                return true;
            }else{
                $this -> errMes = '操作失败';
                $this -> errSql = $handleModelMenu ->getLastSql();
                return false;
            }
        }else{
            //添加目录
        }

    }




    private static function tree($data,$field = 'parent_menu_id',$pIDValue = 0, $backData=[]){
        foreach($data as $k=>$v){
            if($v[$field] == $pIDValue){
                $backData[] = $v;
                unset($data[$k]);
                $backData = self::tree($data,$field,$v['menu_id'], $backData);
            }
        }
        return $backData;//剩下的data就是没有第一级目录的链接或者下级目录
    }
}