<?php
$reurl='../app/weixin/index.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&id='.$weixinid;
require_once './token.php';
$query = "SELECT * FROM $met_weixin_menu where lang='$lang' and weixinid='$weixinid' ORDER BY list_order ASC";
$result=$db->query($query);
while($list= $db->fetch_array($result)){
	if($list['class']==1){
		$list_array1[]=$list;
	}else{
		$list_array2[$list['preclass']][]=$list;
	}
}
if($list_array1){
	foreach($list_array1 as $key=>$val){
		if($val[type]!=1&&$list_array2[$val[id]]){
			$list_array1[$key][type]=1;
			$query="update $met_weixin_menu set type=1 where id='$val[id]'";
			$db->query($query);
		}
	}
	foreach($list_array1 as $key=>$val){
		if($val[type]==1){
			$button2=array();
			if($list_array2[$val[id]]){
				foreach($list_array2[$val[id]] as $key1=>$val1){
					if($val1[type]==2){
						$button2[]=array('type'=>'view','name'=>$val1[name],'url'=>$val1[url]);
					}else{
						$button2[]=array('type'=>'click','name'=>$val1[name],'key'=>'key'.$val1[id]);
					}
				}
				$button1[button][]=array('name'=>$val[name],'sub_button'=>$button2);
			}else{
				$button1[button][]=array('type'=>'click','name'=>$val[name],'key'=>'key'.$val[id]);
			}
		}else{
			if($val[type]==2){
				$button1[button][]=array('type'=>'view','name'=>$val[name],'url'=>$val[url]);
			}else{
				$button1[button][]=array('type'=>'click','name'=>$val[name],'key'=>'key'.$val[id]);
			}
		}
	}
	$body=json_encode($button1);
	$body=preg_replace("#\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $body);
	$met_file="/cgi-bin/menu/create?access_token=$met_weixin_access_token";
	$post_data=$body;
	$menu=json_decode(curl_post($post_data,30));
}else{
	$met_file="/cgi-bin/menu/delete?access_token=$met_weixin_access_token";
	$post_data=$body;
	$menu=json_decode(curl_post($post_data,30));
}
if($menu->errmsg!='ok'){
	metsave('../app/weixin/index.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs,'添加菜单，错误代码。'.$menu->errcode,$depth);
	die();
}

?>