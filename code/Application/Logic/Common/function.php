<?php
/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:09
 */
//手机号加密
function encodeTel($tel){
    return $tel.C('encode_tel_salt');
}
//手机号解密
function decodeTel($tel){
    return $tel-1;
}
//真实姓名加密
function encodeName($name){
    return $name.C('encode_tel_salt');
}
//真实姓名解密
function decodeName($name){
    return $name;
}
//省份证号码加密
function encodeNo($no){
    return $no.C('encode_tel_salt');
}
//身份证号码解密
function decodeNo($no){
    return $no;
}