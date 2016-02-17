<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
require_once '../common.inc.php';
//定义TOKEN，必须与微信公众平台设置的TOKEN一致
require_once 'wx.class.php';
if($action=='attention'){
$query="update $met_config set value='1' where name='met_weixin_attention'";
$db->query($query);
echo 'ok';
die();
}
$query="select * from $met_weixin_accounts where originalid='$wid'";
$weixin=$db->get_one($query);
define("TOKEN", $weixin[token]);
$wechatObj = new wechatCallbackapi();
if($wechatObj->is_valid()){
	$wechatObj->valid();
}else{
	$wechatObj->responseMsg();
}	
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>