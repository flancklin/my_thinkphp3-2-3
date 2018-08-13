<?php
namespace Home\Controller;


use Home\Service\QrCodeService;
use Think\Controller;

class QrCodeController extends Controller{
    public function image(){
        $imageUrl = QrCodeService::createPng();
        echo '<img src="'.$imageUrl .'">';
    }

    public function show(){
        QrCodeService::justShow();
    }
}