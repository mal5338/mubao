<?php
require_once './export.func.php';
$met_host='api.weixin.qq.com';
$query="select * from $met_weixin_accounts where id='$weixinid'";
$weixin=$db->get_one($query);
if(1){
	$met_file="/cgi-bin/token?grant_type=client_credential&appid={$weixin[AppId]}&secret={$weixin[AppSecret]}";
	$post=array();
	$access_token=json_decode(curl_post($post,30));
	if($access_token->access_token){
		$met_weixin_access_token=$access_token->access_token;
		$met_weixin_expires_in=$access_token->expires_in+$m_now_time;
	}else{
		if($access_token_error!=1)metsave($reurl,'获取access_token失败，错误代码。'.$access_token->errcode,$depth);
	}
}
?>