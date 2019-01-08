<?php
 return [
     //路由设置
     "URL_ROUTER_ON"         =>  true,
     'URL_MAP_RULES'         =>array(
         'learn'             => 'Manage/Login/login?role=plat',
         'finance'             => 'Manage/Login/login?role=finance',
         'partner'             => 'Manage/Login/login?role=partner',
         'operator'             => 'Manage/Login/login?role=operator',
         'agent'             => 'Manage/Login/login?role=agent',
         'merchant'             => 'Manage/Login/login?role=merchant',
         'goods'             => 'Manage/Login/login?role=goods',
         'login'             => 'Manage/Login/login?role=login',
         'forget'             => 'Manage/Login/forget'
     )
 ];
?>