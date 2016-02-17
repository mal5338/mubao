<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='editor'||$action=='add'){
	//本地数据保存
	if($imgnum>0){
		for($i=1;$i<=$imgnum;$i++){
			$displayimg = "displayimg".$i;
			$displayname = "displayname".$i;
			$displayurl = "displayurl".$i;
			$$displayname=str_replace(array('|','*'),'_',$$displayname);
			if(!strstr($$displayurl,"http://"))$$displayurl="http://".$$displayurl;
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
		metsave('../app/weixin/reply.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&id='.$weixinid,'最多显示10条',$depth);
	}
	if($action=='editor'){
		$query="update $met_weixin_reply set name='$wenxin_reply_name',type='$wenxin_reply_type',text='$met_weixin_text',imgtext='$displayimglist',description='$wenxin_reply_description',columns='{$mod}-{$weixinlang}-{$class1}-{$class2}-{$class3}',recommend='$recommend',num='$num',keywords='$wenxin_reply_keywords' where id='$id'";
		$db->query($query);
	}else{		
		$query="insert into $met_weixin_reply set name='$wenxin_reply_name',type='$wenxin_reply_type',genre='3',text='$met_weixin_text',imgtext='$displayimglist',description='$wenxin_reply_description',columns='{$mod}-{$weixinlang}-{$class1}-{$class2}-{$class3}',recommend='$recommend',num='$num',weixinid='$weixinid',lang='$lang',keywords='$wenxin_reply_keywords'";
		$db->query($query);
		$id=$db->insert_id();
	}
	$query="delete from $met_weixin_keywords where replyid='$id'";
	$db->query($query);
	$wenxin_reply_keywords_alls=explode('|',$wenxin_reply_keywords_all);
	foreach($wenxin_reply_keywords_alls as $key=>$val){
		if($val){
			$query="insert into $met_weixin_keywords set weixinid='$weixinid',replyid='$id',word='$val',type='1'";
			$db->query($query);
		}
	}
	
	$wenxin_reply_keywords_contains=explode('|',$wenxin_reply_keywords_contain);
	foreach($wenxin_reply_keywords_contains as $key=>$val){
		if($val){
			$query="insert into $met_weixin_keywords set weixinid='$weixinid',replyid='$id',word='$val',type='2'";
			$db->query($query);
		}
	}
	metsave('../app/weixin/reply.php?anyid='.$anyid.'&lang='.$lang.'&cs='.$cs.'&id='.$weixinid,'',$depth);
}
?>