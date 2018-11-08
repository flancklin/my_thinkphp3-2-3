<?php

namespace Logic\Logic;


use Think\Log;
use Think\Model;

class Logic {
    //【???事务】考虑有没有必要开启事务
    /**
     * 对数据库的操作CURD
     * C---create(createOne/createMore)
     * U---update(update(false)/update(true)修改一条或者多条记录。防误操作)
     * R---read(readOne/readMore)
     * D---delete(deleteOne/deleteMore)==delete()单删除，delete(true)多删除
     */
    public $showErrMes = '';
    public $errSql = '';
    public $code = 0;
    public $construct = true;

    protected $isShowErrSql = true;  //错误的时候，是否输出错误的sql语句【增删改的时候，由本字段控制是否输出错误sql，但查询的时候，全屏实际情况操作】
    public $isShowSelectEmptySql = false; //查询的时候，数据为空。输出查询的sql

    const CODE_ERROR_PARAM = 101;//参数判断过程出错
    const CODE_ERROR_PARAM_MODEL = 102;//参数判断过程出错(自动验证)

    const CODE_ERROR_INSERT = 210;//添加操作数据库失败
    const CODE_ERROR_INSERT_BEFORE = 211;//添加操作数据库失败(前置操作)
    const CODE_ERROR_INSERT_AFTER = 212;//添加操作数据库失败(后置操作)
    const CODE_ERROR_DELETE = 220;//删除操作数据库失败(假删除，改状态而已)
    const CODE_ERROR_DELETE_BEFORE = 221;//删除操作数据库失败(假删除，改状态而已)（前置操作）
    const CODE_ERROR_DELETE_AFTER = 222;//删除操作数据库失败(假删除，改状态而已)（后置操作）
    const CODE_ERROR_UPDATE = 230;//更新操作数据库失败
    const CODE_ERROR_UPDATE_BEFORE = 231;//更新操作数据库失败
    const CODE_ERROR_UPDATE_AFTER = 232;//更新操作数据库失败
    const CODE_ERROR_DELETE_TRUE = 290;//更新操作数据库失败（删除记录）
    const CODE_ERROR_DELETE_TRUE_BEFORE = 291;//更新操作数据库失败（删除记录）
    const CODE_ERROR_DELETE_TRUE_AFTER = 292;//更新操作数据库失败（删除记录）


    /**
     * 写错误日志
     * @param $code
     * @param $showErrMes
     * @param string $logMes
     * @param array $param
     * @param string $errSql
     * @param int $logLevel
     */
    protected function writeError($code, $showErrMes, $logMes = '', $param = [], $errSql = '', $logLevel = 0) {
        $this->code = $code;
        $this->showErrMes = $showErrMes;
        $this->errSql = $errSql;
        if ($logLevel) {
            //log   写日志
            $data = [
                'code' => $this->code,
                'show_mes' => $this->showErrMes,
                'log_mes' => empty($logMes) ? $this->showErrMes : $logMes,
                'is_show_err_sql' => $this->isShowErrSql,
                'is_show_select_empty_sql' => $this->isShowSelectEmptySql,
                'err_sql' => $this->errSql,
                'log_level' => $logLevel,
                'param' => $param,
            ];
        }
    }

    /**
     * 一次读取多条记录
     * @param $dbTable
     * @param array $where
     * @param string $field
     * @param string $orderBy
     * @param int $page
     * @param int $pageSize
     * @param string $backKeyValueField
     * @return array
     */
    public function readMore($dbTable, $where = [], $field = '', $orderBy = '', $page = 0, $pageSize = 0, $backKeyValueField = '') {
        $model = $this->model($dbTable);
        if ($model === false) return [];
        if (
            $field
            && $backKeyValueField
            && strpos($field, ',' . $backKeyValueField . ',') === false
            && strpos($field, $backKeyValueField . ',') !== 0
            && strpos($field, ',' . $backKeyValueField) !== (strlen($field) - 1)
        ) {
            $this->showErrMes = '键值对的field不在查询字段中';
            return [];
        }
        $where && $model->where($where);
        $field && $model->field($field);
        $orderBy && $model->order($orderBy);
        $page && $pageSize && $model->limit(($page - 1) * $pageSize, $pageSize);
        $result = $model->select();
        $result || $this->isShowSelectEmptySql && $this->errSql = $model->getLastSql();
        if ($result && $backKeyValueField) {
            if (!isset($result[0][$backKeyValueField])) {
                $this->showErrMes = '键值对的field不在查询字段中哦';
                return [];
            }
            $newResult = [];
            foreach ($result as $re) {
                $newResult[$re[$backKeyValueField]] = $re;
            }
            $result = $newResult;
            unset($newResult);
        }
        return $result;
    }

    /**
     * 一次读取一条记录
     * @param array $where
     * @param string $field
     * @param string $order
     * @return bool
     */
    public function readOne($dbTable, $where = [], $field = '', $order = '') {
        $model = $this->model($dbTable);
        if ($model === false) return false;
        $where && $model->where($where);
        $field && $model->field($field);
        $order && $model->order($order);
        $result = $model->find();
        $result || $this->isShowSelectEmptySql && $this->errSql = $model->getLastSql();
        return $result;
    }

    /**
     * 添加一条记录
     * @param $data
     * @param string $dbTable //为空则表示是当前logic对应的表
     * @return bool
     */
    public function createOne($dbTable, $data) {
        if (empty($data)) {
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空', '添加单条记录，数据为空');
            return false;
        }
        $model = $this->model($dbTable);
        if ($model === false) return false;
        $data = $model->create($data, Model::MODEL_INSERT);
        if (!$data) {
            $this->writeError(
                self::CODE_ERROR_PARAM_MODEL,
                empty($model->getError()) ? '保存失败，数据非法' : $model->getError(),
                '添加单条记录，参数非法：' . $model->getError(),
                ['data' => $data]
            );
            return false;
        }
        //写数据库
        $result = $model->data($data)->add();
        if (!$result) {
            $this->writeError(
                self::CODE_ERROR_INSERT,
                '保存失败',
                '添加单条记录失败：' . $model->getError(),
                ['result' => $result, 'data' => $data],
                $this->isShowErrSql ? $model->getLastSql() : '',
                Log::EMERG
            );
        }
        return $result;
    }

    /**
     * 添加多条记录
     * @param $data
     * @return bool
     */
    public function createMore($dbTable, $data) {
        if (empty($data) || !is_array($data)) {
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空', '批量添加，数据为空');
            return false;
        }
        $model = $this->model($dbTable);
        if ($model === false) return false;
        //数据有效性验证（改得时候也是全部数据重新提交已一次，所以验证模式都是【model_insert】）
        foreach ($data as &$oneData) {
            $oneData = $model->create($oneData, Model::MODEL_INSERT);
            if (!$oneData) {
                $this->writeError(
                    self::CODE_ERROR_PARAM_MODEL,
                    empty($model->getError()) ? '保存失败，数据非法' : $model->getError(),
                    '批量添加参数非法：' . $model->getError());
                return false;
            }
        }
        if ($model->before_insert_and_update($data, '', true) === false) {
            $this->writeError(
                self::CODE_ERROR_INSERT_BEFORE,
                empty($model->getError()) ? '保存失败了' : $model->getError(),
                '批量添加,前置操作失败：' . $model->getError());
            return false;
        }
        //写数据库
        $result = $model->addAll($data, '', true);
        if (!$result) {
            $this->writeError(
                self::CODE_ERROR_INSERT,
                '保存失败啦',
                '批量添加失败：' . $model->getError(),
                ['data' => $data],
                $this->isShowErrSql ? $model->getLastSql() : '');
        }
        if ($model->after_insert_and_update($data) === false) {
            $this->writeError(
                self::CODE_ERROR_INSERT_BEFORE,
                empty($model->getError()) ? '保存失败了' : $model->getError(),
                '批量添加,前置操作失败：' . $model->getError(),
                ['data' => $data]
            );
            return false;
        }
        return $result;
    }

    protected function delete($dbTable, $where, $upData, $deleteMore = false) {
        if (empty($where)) {
            $this->writeError(self::CODE_ERROR_PARAM, '删除条件不足', '删除时where未为空[假删除]');
            return false;
        }
        if (empty($upData)) {
            $this->writeError(self::CODE_ERROR_PARAM, '删除条件不足哦', '删除时upData未为空[假删除]');
            return false;
        }
        $model = $this->model($dbTable);
        if (!$model) return false;
        $model->where($where);
        $deleteMore !== true || $model->limit(1);
        $result = $model->save($upData);
        //结果处理
        if ($result === false) {
            $this->writeError(
                self::CODE_ERROR_DELETE,
                '删除失败',
                '执行删除失败：' . $model->getError(),
                ['result' => $result, 'where' => $where, 'data' => $upData],
                $this->isShowErrSql ? $model->getLastSql() : '',
                Log::ALERT);
        } elseif ($result === 0) {//数据库中没有找到这一条记录
        }
        return $result;
    }

    protected function update($dbTable, $where, $upData) {
        if (empty($where)) {
            $this->writeError(self::CODE_ERROR_PARAM, '更新条件不足', '更新时where未为空');
            return false;
        }
        if (empty($upData)) {
            $this->writeError(self::CODE_ERROR_PARAM, '更新条件不足哦', '更新时upData未为空');
            return false;
        }
        $model = $this->model($dbTable);
        if (!$model) return false;

        //检验数据
        $upData = $model->create($upData, Model::MODEL_UPDATE);
        if (!$upData) {
            $this->writeError(
                self::CODE_ERROR_PARAM,
                empty($model->getError()) ? '修改失败' : $model->getError(),
                '更新数据库，数据未通过验证规则:' . $model->getError(),
                ['where' => $where, 'up_data' => $upData]
            );
            return false;
        }
        //修改数据库
        $model->where($where);
        $result = $model->save($upData);
        //结果处理
        if ($result === false) {
            $this->writeError(
                self::CODE_ERROR_DELETE,
                '删除失败',
                '执行删除失败：' . $model->getError(),
                ['result' => $result, 'where' => $where, 'data' => $upData],
                $this->isShowErrSql ? $model->getLastSql() : '',
                Log::ALERT);
        } elseif ($result === 0) {//数据库中没有找到这一条记录
        }
        return $result;
    }
}