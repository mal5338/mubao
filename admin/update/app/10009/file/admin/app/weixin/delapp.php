<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
$depth='../';
require_once $depth.'../login/login_check.php';
if($action='del'){
$query ="DROP TABLE IF EXISTS `{$tablepre}weixin_menu`;";
$db->query($query);
$query ="DROP TABLE IF EXISTS `{$tablepre}weixin_reply`;";
$db->query($query);
$query ="DROP TABLE IF EXISTS `{$tablepre}weixin_accounts`;";
$db->query($query);
$query ="DROP TABLE IF EXISTS `{$tablepre}weixin_keywords`;";
$db->query($query);



$tables_name=explode('|',$met_tablename);
$tables_name_str='';
foreach($tables_name as $key=>$val){
	if(stripos("|weixin_menu|weixin_reply|weixin_accounts|weixin_keywords|",'|'.$val.'|')===false)$tables_name_str.=$val.'|';
}
$tables_name_str=trim($tables_name_str,'|');
$query="update $met_config set value='$tables_name_str' where name='met_tablename'";
$db->query($query);

$query="select * from $met_app where id='$id' and download=1";
$app=$db->get_one($query);
if($app['file'])deldir('../'.$app['file']);
$query="delete from $met_app where id='$id' and download=1";
$db->query($query);
unlink('../../../include/interface/weixin.php');
unlink('../../../include/interface/wx.class.php');
unlink('../../../public/app/weixin/image/weixin.jpg');
}
echo $lang_appuninstall;
metsave('../app/dlapp/dlapp.php?anyid='.$anyid.'&lang='.$lang,'',$depth);
# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>