<?php
/**
 * Created by PhpStorm.
 * User: fengxiansheng
 * Date: 2018/8/19
 * Time: 14:09
 */

function encodeTel($tel, $salt = ''){
    return $tel+1;
}
function decodeTel($tel, $salt = ''){
    return $tel-1;
}