<?php
namespace Logic\Logic;
use Logic\Model\AddressModel;
use Think\Model;

/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:39
 */
class UserAddressLogic extends Logic {
    public $uid       = 0;
    public $addressId = 0;

    public function __construct($uid, $addressId = 0)
    {
        if(empty($uid)){
            $this->writeError(self::CODE_ERROR_PARAM, '缺少用户信息');
            return false;
        }
        $this->uid = $uid;
        if($addressId && !preg_match(C('reg.number_no_0'), $addressId)){
            $this->writeError(self::CODE_ERROR_PARAM, '地址信息错误');
            return false;
        }
        $this->addressId = $addressId;
    }

    protected function handleModel(){
        return new AddressModel();
    }

    /**
     * 添加或者修改用户得地址
     * @param $data
     * @return bool
     */
    public function addAndUpdateAddress($data){
        if(empty($data)){
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空');
            return false;
        }
        $where = [];
        if($this->addressId > 0){
            $where['uid']        = $this->uid;
            $where['address_id'] = $this->addressId;
        }
        $handleAddress = $this->handleModel();
        //数据有效性验证（改得时候也是全部数据重新提交已一次，所以验证模式都是【model_insert】）
        if(!$handleAddress->create($data, Model::MODEL_INSERT)){
            $this->writeError(self::CODE_ERROR_PARAM, $handleAddress->getError());
            return false;
        }
        //改/写数据库
        $result = $handleAddress->save($data, $where);
        if(!$result){
            $this->writeError(self::CODE_ERROR_SAVE, '操作失败',$handleAddress->getLastSql());
        }
        return $result;
    }

    /**
     * 删除用户得地址（假删除）
     * @return bool|mixed
     */
    public function deleteAddress(){
        if($this->addressId <= 0){
            $this->writeError(self::CODE_ERROR_PARAM, '地址信息错误');
            return false;
        }
        $handleAddress = $this->handleModel();
        $result = $handleAddress->where(['uid' => $this->uid, 'address_id' => $this->addressId])->find();
        if(empty($result)){
            return true;
        }
        $result = $handleAddress->where(['uid' => $this->uid, 'address_id' => $this->addressId])->setField(['address_status' => AddressModel::ADDRESS_STATUS_DEL]);
        if(!$result){
            $this->writeError(self::CODE_ERROR_SET_FIELD, '操作失败', $handleAddress->getLastSql());
        }
        return $result;
    }

}