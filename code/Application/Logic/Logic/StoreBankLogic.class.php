<?php

namespace Logic\Logic;


use Logic\Model\BankCardModel;
use Logic\Model\BaseModel;
use Logic\Model\StoreBankModel;
use Logic\Model\UserAddressModel;
use Logic\Model\UserBankModel;
use Think\Log;
use Think\Model;

/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:39
 */
class StoreBankLogic extends Logic {
    private $storeId = 0;
    private $bankId = 0;

    function __construct($storeId, $bankId = 0) {
        $this->construct = false;
        if(!preg_match(BaseModel::REG_POS_INTEGER, $storeId)){
            $this->writeError(self::CODE_ERROR_PARAM, '缺少店铺信息', 'storeId为空');
            return false;
        }
        if($bankId && !preg_match(BaseModel::REG_POS_INTEGER, $bankId)){
            $this->writeError(self::CODE_ERROR_PARAM, '银行卡信息错误', '银行卡id未通过正则验证');
            return false;
        }
        $this->storeId = $storeId;
        $this->bankId = $bankId;
        $this->construct = true;
    }
    protected function model($dbTable = ''){
        return new StoreBankModel();
    }
    /**
     * 添加一条记录
     * @param $data
     * @return bool
     */
    public function createOne($data) {
        if (!$this->construct) return false;
        if (empty($data)) {
            $this->writeError(
                self::CODE_ERROR_PARAM,
                '数据为空',
                '添加单条记录，数据为空');
            return false;
        }

        $model = $this->model();
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
        isset($data['default']) && $data['default'] == UserBankModel::DEFAULT_YES && $model->where(['storeId' => $data['storeId'], 'default' => UserBankModel::DEFAULT_YES])->setField(['default' => UserBankModel::DEFAULT_NO]);
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
     * 删除用户得地址（假删除）
     * @param bool $deleteMore 删除一条记录还是多条记录
     * @return bool
     */
    public function delete($deleteMore = false) {
        if (!$this->construct) return false;
        //整理数据
        $where = [];
        $where['storeId'] = $this->storeId;
        $this->bankId && $where['bank_id'] = $this->bankId;

        $upData = [];
        $upData['delete'] = StoreBankModel::DELETE_YES;
        //写数据库
        $model = $this->model();
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
            $result = true;
        }
        return $result;
    }

    /**
     * 更新地址
     * @param $upData
     * @param array $where
     * @return bool
     */
    public function update($upData, $where = []) {
        if (!$this->construct) return false;
        if (empty($upData)) {
            $this->writeError(self::CODE_ERROR_PARAM, '修改数据为空', '修改数据为空');
            return false;
        }
        if(empty($this->bankId)){
            $this->writeError(self::CODE_ERROR_PARAM, '银行卡信息为空', '修改数据为空');
            return false;
        }
        //整理数据
        $where['storeId'] = $this->storeId;

        //检验数据
        $model = $this->model();
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
        //写数据库
        isset($upData['default']) && $upData['default'] == StoreBankModel::DEFAULT_YES && $model->where(['storeId' => $where['storeId'], 'default' => StoreBankModel::DEFAULT_YES])->setField(['default' => StoreBankModel::DEFAULT_NO]);
        $result = $model->where($where)->save($upData);
        //结果处理
        if (!$result) {//result =0 或者false
            $this->writeError(
                self::CODE_ERROR_UPDATE,
                '修改失败',
                '修改数据库失败：' . $model->getError(),
                ['result' => $result, 'where' => $where, 'up_data' => $upData],
                $this->isShowErrSql ? $model->getLastSql() : '',
                Log::ALERT
            );
        }
        return $result;
    }
}