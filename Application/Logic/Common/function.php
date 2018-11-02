<?php
/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:09
 */

function encodeTel($tel){
    return $tel.C('encode_tel_salt');
}
function decodeTel($tel, $salt = ''){
    return $tel-1;
}