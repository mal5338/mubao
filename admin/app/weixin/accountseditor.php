<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
/*
if(!$met_weixin_attention&&$action=='add'){
metsave('../app/weixin/accounts.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&action=attention','请关注MetInfo官方微信！！！',$depth);
}
*/

//获取列表页
$query = "SELECT * FROM $met_weixin_accounts where id='$id'";
$accounts=$db->get_one($query);
if(!$accounts[token]){
	$accounts[token]=met_rand(8);
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';

//CSS
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$colspan=7;
//包含模板文件
include template_app('weixin/accountseditor',$EXT="html");
footer();
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>