<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action){
	if($action=="delete"){//删除一条数据
		$query = "delete from $met_weixin_menu where id='$id'";
		$db->query($query);
	}
	if($action=="del"){//删除多条数据
		$allidlist=explode(',',$allid);
		foreach($allidlist as $key=>$val){
			$query = "delete from $met_weixin_menu where id='$val'";
			$db->query($query);
		}
	}
	if($action=="editor"){
		$allidlist=explode(',',$allid);
		$adnum = count($allidlist)-1;
		for($i=0;$i<$adnum;$i++){
			$list_order     = "list_order_".$allidlist[$i];
			$list_order     = $$list_order;
			$name   = "name_".$allidlist[$i];
			$name   = $$name;
			$query="update $met_weixin_menu set
					list_order              = '$list_order',
					name             = '$name'
					where id='$allidlist[$i]'";
			$db->query($query);
		}
	}
	require_once './weixin.php';
	metsave('../app/weixin/index.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs.'&id='.$weixinid,'',$depth);
}
//
$query = "SELECT * FROM $met_weixin_accounts where id='$id'";
$weixin=$db->get_one($query);
if(!($weixin[AppId]&&$weixin[AppSecret])){
	metsave('../app/weixin/accountseditor.php?lang='.$lang.'&anyid='.$anyid.'&cs=5&id='.$id,'请填写开发者凭据AppId和AppSecret',$depth);
}
//获取列表页
$query = "SELECT * FROM $met_weixin_menu where weixinid='$id' and lang='$lang' ORDER BY list_order ASC";
$result=$db->query($query);
while($list= $db->fetch_array($result)){
	switch($list[type]){
		case 1:
			$list[type]='菜单';
		break;
		case 2:
			$list[type]='打开网址';
		break;
		case 3:
			$list[type]='文字回复';
		break;
		case 4:
			$list[type]='图文回复';
		break;	
		case 5:
			$list[type]='网站内容推送';
		break;		
	}
	if($list['class']==1){
		$list_array1[]=$list;
	}else{
		$list_array2[$list['preclass']][]=$list;
	}
}
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
//CSS
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$colspan=7;
//包含模板文件
include template_app('weixin/index',$EXT="html");
footer();
?>