<?php
namespace Logic\Logic;


use Logic\Model\UserAddressModel;
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
        return new UserAddressModel();
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
        $where = [];
        $where['address_id']    = $this->addressId;
        $where['uid']           = $this->uid;
        $data = [];
        $data['address_status'] = UserAddressModel::ADDRESS_STATUS_DEL;

        $handleAddress = $this->handleModel();
        $result = $handleAddress->where($where)->save($data);
        if($result === false){
            $this->writeError(self::CODE_ERROR_UPDATE, '操作失败', $this->showErrSql ? $handleAddress->getLastSql() : '');
        }elseif($result === 0){//数据库中没有找到这一条记录
            $result = true;
        }
        return $result;
    }

    /**
     * 添加或者修改用户得地址
     * @param $data
     * @return bool
     */
    public function updateAddress($data){
        if(empty($data)){
            $this->writeError(self::CODE_ERROR_PARAM, '数据为空');
            return false;
        }
        if($this->addressId <= 0){
            $this->writeError(self::CODE_ERROR_PARAM, '缺少地址信息');
            return false;
        }

        $handleAddress = $this->handleModel();
        //数据有效性验证（改得时候也是全部数据重新提交已一次，所以验证模式都是【model_insert】）
        if(!$handleAddress->create($data, Model::MODEL_INSERT)){
            $this->writeError(self::CODE_ERROR_PARAM, $handleAddress->getError());
            return false;
        }
        //改/写数据库
        $where = [];
        $where['address_id'] = $this->addressId;
        $where['uid']        = $this->uid;
        $result = $handleAddress->where($where)->save($data);
        if(!$result){//result =0 或者false
            $this->writeError(self::CODE_ERROR_UPDATE, '操作失败',$this->showErrSql ? $handleAddress->getLastSql() : '', '', ['result' => $result, 'param' => $data]);
        }
        return $result;
    }

}