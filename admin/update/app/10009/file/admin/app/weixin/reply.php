<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action){
	if($action=="delete"){//删除一条数据
		$query = "delete from $met_weixin_reply where id='$id'";
		$db->query($query);
	}
	if($action=="del"){//删除多条数据
		$allidlist=explode(',',$allid);
		foreach($allidlist as $key=>$val){
			$query = "delete from $met_weixin_reply where id='$val'";
			$db->query($query);
		}
	}
	metsave('../app/weixin/reply.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs.'&id='.$weixinid,'',$depth);
}
//获取列表页
$query = "SELECT * FROM $met_weixin_reply where weixinid='$id' and lang='$lang' ORDER BY id DESC";
$result=$db->query($query);
while($list= $db->fetch_array($result)){
	switch($list[genre]){
		case 1:
			$list[genre1]='系统默认';
		break;
		case 2:
			$list[genre1]='系统默认';
		break;
		case 3:
			$list[genre1]='自定义关键词';
		break;	
	}
	$replys[]=$list;
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
//CSS
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$colspan=7;
//包含模板文件
include template_app('weixin/reply',$EXT="html");
footer();
?>