<?php
$depth='../';
require_once $depth.'../login/login_check.php';
require_once './export.func.php';
if($cs!=3&&$cs!=2){
Header("Location: ./accounts.php?anyid=$anyid&lang=$lang&cs=5");
die();
}
if($action=='modify'){
	$met_host_tmp=$met_host;
	$reurl='../app/weixin/setapp.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs;
	require_once './token.php';

	$met_host="api.weixin.qq.com";
	$met_file="/cgi-bin/qrcode/create?access_token=$met_weixin_access_token";
	$post_data='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 1}}}';
	$ticket=json_decode(curl_post($post_data,30));
	if(!$ticket->ticket){
		echo '获取ticket失败，错误代码。'.$access_token->errcode;
		die();
	}
	$met_weixin_ticket=UrlEncode($ticket->ticket);
	/*
	$met_host='mp.weixin.qq.com';
	$met_file="/cgi-bin/showqrcode?ticket=".UrlEncode($ticket->ticket);
	$post_data='';
	$img=curl_post($post_data,30);
	echo $img;
	die();
*/
	$met_host=$met_host_tmp;
	require_once $depth.'../include/config.php';
	metsave('../app/weixin/setapp.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,'',$depth);
}
//单选款选中
$met_weixin_radio1[$met_weixin_radio]="checked='checked'";
//复选框选中
$met_weixin_checkbox1=$met_weixin_checkbox==1?"checked='checked'":"";
if($cs==2){
	$query="select * from $met_weixin_reply where weixinid='$id' and lang='$lang' and genre='1'";
	$attention=$db->get_one($query);
	$query="select * from $met_weixin_reply where weixinid='$id' and lang='$lang' and genre='2'";
	$reply=$db->get_one($query);
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template_app('weixin/setapp',$EXT="html");
footer();
?>