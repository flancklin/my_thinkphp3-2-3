<?php
namespace Logic\Logic;

use Logic\Model\AddressModel;
class Logic{
    public $errMes = '';
    public $errSql = '';
    public $code   = 0;

    const CODE_ERROR_PARAM        = 101;//参数判断过程出错
    
    const CODE_ERROR_SAVE         = 202;//save方法操作数据库失败
    const CODE_ERROR_UPDATE       = 203;//update方法操作数据库失败
    const CODE_ERROR_SET_FIELD    = 204;//setField方法操作失败

    /**
     * 写错误日志
     * @param $code
     * @param $errMes
     * @param string $errSql
     * @param int $logLevel
     * @param array $param
     */
    protected function writeError($code, $errMes, $errSql = '', $logLevel = 0, $param = []){
        $this->code   = $code;
        $this->errMes = $errMes;
        $this->errSql = $errSql;
        if($logLevel){
         //log   写日志
        }
    }

    /**
     * 对数据库进行查询（多条记录）
     * @param array $where
     * @param string $field
     * @param string $orderBy
     * @param int $page
     * @param int $pageSize
     * @return mixed
     */
    public function selectData($where = [], $field = '', $orderBy = '', $page = 0, $pageSize = 0){
        $handleModel = $this->handleModel();
        $where && $handleModel->where($where);
        $field && $handleModel->field($field);
        $orderBy && $handleModel->order($orderBy);
        $page && $pageSize && $handleModel->limit(($page-1) * $pageSize, $pageSize);
        $result =  $handleModel->select();
        if(empty($result)){
            $this->errSql = $handleModel->getLastSql();
        }
        return $result;
    }

    /**
     * 对数据库进行查询（单条记录）
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function findData($where = [], $field = ''){
        $handleModel = $this->handleModel();
        $where && $handleModel->where($where);
        $field && $handleModel->field($field);
        $result =  $handleModel -> find();
        if(empty($result)){
            $this->errSql = $handleModel->getLastSql();
        }
        return $result;
    }
}