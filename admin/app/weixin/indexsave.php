<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='editor'||$action=="add"){
//本地数据保存
	if($imgnum>0){
		for($i=1;$i<=$imgnum;$i++){
			$displayimg = "displayimg".$i;
			$displayname = "displayname".$i;
			$displayurl = "displayurl".$i;
			if(!strstr($$displayurl,"http://"))$$displayurl="http://".$$displayurl;
			$$displayname=str_replace(array('|','*'),'_',$$displayname);
			if($$displayname||$$displayimg){
				if($i==1){
					$displayimglist=$$displayname.'*'.$$displayurl.'*'.$$displayimg;
				}else{
					$displayimglist=$displayimglist.'|'.$$displayname.'*'.$$displayurl.'*'.$$displayimg;
				}
			}
		}
	}
	if($num>10){
		metsave('../app/weixin/index.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&id='.$weixinid,'最多显示10条',$depth);
	}
	if(!strstr($met_weixin_url,"http://"))$met_weixin_url="http://".$met_weixin_url;
	if($id){
		$query="update $met_weixin_menu set list_order='$weixin_menu_list_order',name='$weixin_menu_name',type='$weixin_menu_type',class='$weixin_menu_local_level',preclass='$weixin_menu_local_pre',text='$met_weixin_text',imgtext='$displayimglist',description='$weixin_menu_description',url='$met_weixin_url',columns='{$mod}-{$weixinlang}-{$class1}-{$class2}-{$class3}',recommend='$recommend',num='$num' where id='$id'";
	}else{
		$query="insert into $met_weixin_menu set list_order='$weixin_menu_list_order',name='$weixin_menu_name',type='$weixin_menu_type',class='$weixin_menu_local_level',preclass='$weixin_menu_local_pre',text='$met_weixin_text',imgtext='$displayimglist',description='$weixin_menu_description',url='$met_weixin_url',columns='{$mod}-{$weixinlang}-{$class1}-{$class2}-{$class3}',recommend='$recommend',num='$num',weixinid='$weixinid',lang='$lang'";
	}
	$db->query($query);
//微信API，保存数据
require_once './weixin.php';
metsave('../app/weixin/index.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&id='.$weixinid,'',$depth);
}
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>
