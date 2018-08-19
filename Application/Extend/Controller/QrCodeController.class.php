<?php
namespace Extend\Controller;


use Extend\Extend\QrCodeExtend;
use Think\Controller;

class QrCodeController extends Controller{
    public function image(){
        $imageUrl = QrCodeExtend::createPng();
        echo '<img src="'.$imageUrl .'">';
    }

    public function show(){
        QrCodeExtend::justShow();
    }
}