<?php

namespace Logic\Logic;

use Logic\Model\BaseModel;
use Logic\Model\User\UserAccountModel;
use Logic\Model\User\UserAddressModel;
use Logic\Model\User\UserBankModel;
use Logic\Model\User\UserInfoModel;
use Logic\Model\User\UserMoneyLogModel;

/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:39
 */
class UserLogic extends Logic {
    private $uid = 0;
    //???有个地方。就是__construct中的uid。如果是对单个用户操作。不存在问题。如果是批量查询呢，很多用户的uid
    function __construct($uid = 0) {
        $this->construct = false;
        //???待处理，这个ACTION_NAME是错误的
        if (ACTION_NAME != 'createOne' && !preg_match(BaseModel::REG_POS_INTEGER, $uid)) {
            $this->writeError(self::CODE_ERROR_PARAM, '缺少用户信息', 'uid为空');
            return false;
        }
        $this->uid = $uid;
        $this->construct = true;
        return true;
    }

    public function model($dbTable = '') {
        if (!$this->construct) return false;
        switch ($dbTable) {
            case 'user_account':
                return new UserAccountModel();
                break;
            case 'user_money_log':
                return new UserMoneyLogModel();
                break;
            case 'user_bank':
                return new UserBankModel();
                break;
            case 'user_address':
                return new UserAddressModel();
                break;
            case 'user_info':
                return new UserInfoModel();
                break;
            default:
                $this->writeError(self::CODE_ERROR_PARAM, '数据库链接不能为空', 'dbTable类型不存在');
                return false;
        }
    }
    public function createOneUser($data){
        if(empty($data)){
            $this->writeError(self::CODE_ERROR_PARAM, '缺少用户信息', 'data为空');
            return false;
        }
        if(isset($data['mobile']) && $this->existUserMobile($data['mobile'])){//检查手机号的唯一性
           return false;
        }
        $account = [];
        $account['user_no'] = $this->buildNo();
        //???事务
        //账户表
        $uid = $this->createOne('user_account', $account);
        $result = false;
        if($uid){
            $data['uid'] = $uid;
            //用户信息基础表
            $result = $this->createOne('user_info', $data);
        }
        return $result;
    }

    private function buildNo(){
        do{
            $no = date('YmdHis').mt_rand(1000, 9999);
        }while($this->readOne('user_account',['user_no' => $no]));
        return $no;
    }

    //*****************************************【user_account账户表】开始***************************************************************************************//

    /**
     * 删除用户账户
     * @return bool
     */
    public function deleteAccount() {
        if (!$this->construct) return false;
        return $this->delete('user_account', ['uid' => $this->uid], ['delete' => UserAccountModel::DELETE_YES]);
    }

    /**
     * 增加用户资金
     * @param $money加的资金数量
     * @param $type加的资金的类型
     * @param $desc资金来源具体描述
     * @return bool
     */
    public function addMoney($money, $type, $desc = '') {
        if (!preg_match(BaseModel::REG_POS_DECIMAL2, $money)) {
            $this->writeError(self::CODE_ERROR_PARAM, '金额有误', 'money正则未通过');
            return false;
        }
        $modelUserAccount = $this->model('user_account');
        if (!$modelUserAccount) return false;

        $oldRecord = $modelUserAccount->where(['uid' => $this->uid])->find();
        if (empty($oldRecord)) {
            $this->writeError(self::CODE_ERROR_PARAM, '账户不存在', '账户不存在');
            return false;
        }
        $upData = ['money' => ['exp', 'money+' . $money]];
        $where = ['uid' => $this->uid];
        //???事务
        $result = $this->update('user_account', $where, $upData);
        $result && $result = $this->writeMoneyLog($type, $money, $oldRecord, UserMoneyLogModel::STATUS_OK, $desc);
        return $result;
    }

    /**
     * 增加用户资金
     * @param $money加的资金数量
     * @param $type加的资金的类型
     * @param $desc资金来源具体描述
     * @return bool
     */
    public function subMoney($money, $type, $desc = '') {
        if (!preg_match(BaseModel::REG_POS_DECIMAL2, $money)) {
            $this->writeError(self::CODE_ERROR_PARAM, '金额有误', 'money正则未通过');
            return false;
        }
        $modelUserAccount = $this->model('user_account');
        if (!$modelUserAccount) return false;
        $oldRecord = $modelUserAccount->where(['uid' => $this->uid])->find();
        if (empty($oldRecord)) {
            $this->writeError(self::CODE_ERROR_PARAM, '账户不存在', '账户不存在');
            return false;
        }
        if ($oldRecord['money'] < $money) {
            $this->writeError(self::CODE_ERROR_PARAM, '账户资金不足', '账户资金不足');
            return false;
        }
        $upData = ['money' => ['exp', 'money-' . $money]];
        $where = ['uid' => $this->uid, 'money' => ['egt', $money]];
        //???事务
        $result = $this->update('user_account', $where, $upData);
        $result && $result = $this->writeMoneyLog($type, $money, $oldRecord, UserMoneyLogModel::STATUS_OK, $desc);
        return $result;
    }

    /**
     * 写用户资金明细
     * @param $type
     * @param $money
     * @param $oldRecord
     * @param int $status
     * @param string $desc
     * @return bool
     */
    public function writeMoneyLog($type, $money, $oldRecord, $status = UserMoneyLogModel::STATUS_WAIT, $desc = '') {
        $data = [];
        $data['uid'] = $this->uid;
        $data['type'] = $type;
        $data['before_money'] = $oldRecord['money'];
        $data['happen_money'] = $money;
        $data['desc'] = $desc;
        $data['status'] = $status;
        return $this->createOne('user_money_log', $data);
    }
    //*****************************************【user_account账户表】结束***************************************************************************************//

    //*****************************************【user_bank银行卡记录表】开始***************************************************************************************//
    /**
     * 添加一条记录
     * @param $data
     * @return bool
     */
    public function createOneBank($data) {
        if (!$this->construct) return false;
        if (empty($data)) {
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空', '添加单条记录，数据为空');
            return false;
        }
        $data['uid'] = $this->uid;
        $result = true;
        //如果新加的银行卡记录设为了默认，则修改以前的所有默认 为非默认
        //???事务
        if (isset($data['default']) && $data['default'] == UserBankModel::DEFAULT_YES) {
            $where = ['uid' => $data['uid'], 'default' => UserBankModel::DEFAULT_YES];
            $upData = ['default' => UserBankModel::DEFAULT_NO];
            $result = $this->update('user_bank', $where, $upData);
        }
        $result && $result = $this->createOne('user_bank', $data);
        return $result;
    }

    /**
     * 删除用户得地址（假删除）
     * @param bool $deleteMore 删除一条记录还是多条记录
     * @return bool
     */
    public function deleteBank($bankId, $deleteMore = false) {
        if (!$this->construct) return false;
        if ($bankId !== -1 && !preg_match(BaseModel::REG_POS_INTEGER, $bankId)) {
            $this->writeError(self::CODE_ERROR_PARAM, '银行卡必要信息为空', 'bankId为空');
            return false;
        }
        //整理数据
        $where = ['uid' => $this->uid];
        $bankId === -1 || $where['bank_id'] = $bankId;
        $upData = ['delete' => UserBankModel::DELETE_YES];
        //写数据库
        return $this->delete('user_bank', $where, $upData, $deleteMore);
    }

    /**
     * 更新地址
     * @param $upData
     * @param array $where
     * @return bool
     */
    public function updateBank($bankId, $upData, $where = []) {
        if (!$this->construct) return false;
        if (!preg_match(BaseModel::REG_POS_INTEGER, $bankId)) {
            $this->writeError(self::CODE_ERROR_PARAM, '银行卡必要信息为空', 'bankId为空');
            return false;
        }
        //整理数据
        $where['bank_id'] = $bankId;
        $where['uid'] = $this->uid;

        $result = true;
        //???事务
        if (isset($upData['default']) && $upData['default'] == UserBankModel::DEFAULT_YES) {
            $w = ['uid' => $where['uid'], 'default' => UserBankModel::DEFAULT_YES];
            $uD = ['default' => UserBankModel::DEFAULT_NO];
            $result = $this->update('user_bank', $w, $uD);
        }
        if ($result) {
            $result = $this->update('user_bank', $where, $upData);
        }
        return $result;
    }
    //*****************************************【user_bank银行卡记录表】结束***************************************************************************************//

    //*****************************************【user_address收货地址记录】开始***************************************************************************************//
    /**
     * 添加一条记录
     * @param $data
     * @return bool
     */
    public function createOneAddress($data) {
        if (!$this->construct) return false;
        if (empty($data)) {
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空', '添加单条记录，数据为空');
            return false;
        }
        $data['uid'] = $this->uid;
        $result = true;
        //???事务
        if (isset($data['default']) && $data['default'] == UserAddressModel::DEFAULT_YES) {
            $w = ['uid' => $data['uid'], 'default' => UserAddressModel::DEFAULT_YES];
            $uD = ['default' => UserAddressModel::DEFAULT_NO];
            $result = $this->update('user_address', $w, $uD);
        }
        $result && $result = $this->createOne('user_address', $data);

        return $result;
    }

    /**
     * 删除用户得地址（假删除）
     * @param bool $deleteMore 删除一条记录还是多条记录
     * @return bool
     */
    public function deleteAddress($addressId, $deleteMore = false) {
        if (!$this->construct) return false;
        if ($addressId !== -1 && !preg_match(BaseModel::REG_POS_INTEGER, $addressId)) {
            $this->writeError(self::CODE_ERROR_PARAM, '收货地址必要信息为空', 'addressId为空');
            return false;
        }
        //整理数据
        $where = ['uid' => $this->uid];
        $addressId === -1 || $where['address_id'] = $addressId;

        $upData = ['delete' => UserAddressModel::DELETE_YES];
        return $this->delete('user_address', $where, $upData);
    }

    /**
     * 更新地址
     * @param $upData
     * @param array $where
     * @return bool
     */
    public function updateAddress($addressId, $upData, $where = []) {
        if (!$this->construct) return false;
        if (!preg_match(BaseModel::REG_POS_INTEGER, $addressId)) {
            $this->writeError(self::CODE_ERROR_PARAM, '收货地址必要信息为空', 'addressId为空');
            return false;
        }
        if (empty($upData)) {
            $this->writeError(self::CODE_ERROR_PARAM, '修改数据为空', '修改数据为空');
            return false;
        }
        //整理数据
        $where['address_id'] = $addressId;
        $where['uid'] = $this->uid;
        $result = true;
        //???事务
        if (isset($upData['default']) && $upData['default'] == UserAddressModel::DEFAULT_YES) {
            $w = ['uid' => $where['uid'], 'default' => UserAddressModel::DEFAULT_YES];
            $uD = ['default' => UserAddressModel::DEFAULT_NO];
            $result = $this->update('user_address', $w, $uD);
        }
        if ($result) {
            $result = $this->update('user_address', $where, $upData);
        }
        return $result;
    }
    //*****************************************【user_address收货地址记录】结束***************************************************************************************//
    //*****************************************【user_info收货地址记录】结束***************************************************************************************//
    public function updateInfo( $upData, $where = []) {
        if (!$this->construct) return false;
        if (empty($upData)) {
            $this->writeError(self::CODE_ERROR_PARAM, '修改数据为空', '修改数据为空');
            return false;
        }
        //整理数据
        $where['uid'] = $this->uid;
        if(isset($upData['mobile']) && $this->existUserMobile($upData['mobile'])){//判断手机号的唯一性
            return false;
        }
        return $this->update('user_address', $where, $upData);
    }

    public function existUserMobile($mobile){
        if(empty($mobile)){
            $this->writeError(self::CODE_ERROR_PARAM, '手机号参数为空', '手机号参数为空');
            return false;
        }
        $existUserMobile = $this->readOne('user_info', ['mobile' => $mobile]);
        if($existUserMobile){
            $this->writeError(self::CODE_ERROR_PARAM, '手机号已被用户占用', '手机号已被用户占用');
            return false;
        }
        return $existUserMobile;
    }
    //*****************************************【user_info收货地址记录】结束***************************************************************************************//


}