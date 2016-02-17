<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='attention'){
	$query="select * from $met_config where name='met_weixin_attention'";
	$attention=$db->get_one($query);
	echo $attention[value];
	die();
}
if($action=='check'){
	metsave("../app/weixin/accountseditor.php?id={$id}&lang={$lang}&anyid={$anyid}&cs={$cs}&action=app",'',$depth);
}
if($action=='ping'){
	$query="select * from $met_weixin_accounts where id='$id'";
	$weixin=$db->get_one($query);
	echo $weixin[checksuc];
	die();
}
if($action=='add'){
	$query="insert into $met_weixin_accounts set name='$wenxin_accounts_name',originalid='$wenxin_accounts_originalid',weixinid='$wenxin_accounts_weixinid',AppId='$wenxin_accounts_AppId',AppSecret='$wenxin_accounts_AppSecret',token='$wenxin_accounts_token',lang='$lang'";
	$db->query($query);
	$id=$db->insert_id();
	
	$query="insert into $met_weixin_reply set name='关注后，发送的回复信息。',type='3',genre='1',text='欢迎关注XXX微信',imgtext='',description='',columns='',recommend='0',num='10',weixinid='$id',lang='$lang'";
	$db->query($query);
	
	$query="insert into $met_weixin_reply set name='默认对话，回复信息。',type='3',genre='2',text='请打开菜单，获取帮助',imgtext='',description='',columns='',recommend='0',num='10',weixinid='$id',lang='$lang'";
	$db->query($query);
}
if($action=='editor'){
	$query="select * from $met_weixin_accounts where id='$id'";
	$weixin=$db->get_one($query);
	if($weixin[originalid]!=$wenxin_accounts_originalid){
		$sql=",checksuc=0";
		$wenxin_accounts_check=0;
	}
	$query="update $met_weixin_accounts set name='$wenxin_accounts_name',originalid='$wenxin_accounts_originalid',weixinid='$wenxin_accounts_weixinid',AppId='$wenxin_accounts_AppId',AppSecret='$wenxin_accounts_AppSecret',token='$wenxin_accounts_token'{$sql} where id='$id'";
	$db->query($query);
}
if($wenxin_accounts_AppId&&$wenxin_accounts_AppSecret){
	$query="update $met_weixin_accounts set AppId='$wenxin_accounts_AppId',AppSecret='$wenxin_accounts_AppSecret' where id='$id'";
	$db->query($query);
	$weixinid=$id;
	$reurl="../app/weixin/accountseditor.php?id={$id}&lang={$lang}&anyid={$anyid}&cs={$cs}&action=editor";
	require_once './token.php';

	$met_host="api.weixin.qq.com";
	$met_file="/cgi-bin/qrcode/create?access_token=$met_weixin_access_token";
	$post_data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 1}}}';
	$ticket=json_decode(curl_post($post_data,30));
}
$query="update $met_weixin_accounts set ticket='".UrlEncode($ticket->ticket)."' where id='$id'";
$db->query($query);

$query="update $met_weixin_accounts set checksuc='1' where id='$id' and checksuc='0'";
$db->query($query);
if($action=='app'){
	metsave('../app/weixin/accounts.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&is_curl=1','',$depth);
}
if($wenxin_accounts_check==2){
	metsave('../app/weixin/accounts.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&is_curl=1','',$depth);
}else{
	metsave("../app/weixin/accountseditor.php?id={$id}&lang={$lang}&anyid={$anyid}&cs={$cs}&action=check",'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
