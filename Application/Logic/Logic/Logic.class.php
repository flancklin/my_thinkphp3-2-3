<?php
namespace Logic\Logic;


use Think\Model;

class Logic{
    public $errMes = '';
    public $errSql = '';
    public $code   = 0;

    protected $showErrSql         = true;  //错误的时候，是否输出错误的sql语句【增删改的时候，由本字段控制是否输出错误sql，但查询的时候，全屏实际情况操作】
    public    $showSelectEmptySql = false;

    const CODE_ERROR_PARAM        = 101;//参数判断过程出错
    
    const CODE_ERROR_INSERT       = 202;//添加操作数据库失败
    const CODE_ERROR_DELETE       = 203;//删除操作数据库失败
    const CODE_ERROR_UPDATE       = 204;//更新操作数据库失败


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
            $this->showSelectEmptySql &&  $this->errSql = $handleModel->getLastSql();
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
            $this->showSelectEmptySql &&  $this->errSql = $handleModel->getLastSql();
        }
        return $result;
    }
    /**
     * 添加或者修改用户得地址
     * @param $data
     * @return bool
     */
    public function insertData($data){
        if(empty($data)){
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空');
            return false;
        }
        $handleModel = $this->handleModel();
        //数据有效性验证（改得时候也是全部数据重新提交已一次，所以验证模式都是【model_insert】）
        if(!$handleModel->create($data, Model::MODEL_INSERT)){
            $this->writeError(self::CODE_ERROR_PARAM, $handleModel->getError());
            return false;
        }
        //写数据库
        $result = $handleModel->data($data)->add();
        if(!$result){
            $this->writeError(self::CODE_ERROR_INSERT, '操作失败',$this->showErrSql ? $handleModel->getLastSql() : '');
        }
        return $result;
    }
}