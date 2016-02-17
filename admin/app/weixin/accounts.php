<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=="delete"||$action=="del"){
	if($action=="delete"){//删除一条数据
		$weixinid=$id;
		$access_token_error=1;
		require_once './token.php';
		$met_file="/cgi-bin/menu/delete?access_token=$met_weixin_access_token";
		$post_data=$body;
		$menu=json_decode(curl_post($post_data,30));
		
		$query = "delete from $met_weixin_accounts where id='$id'";
		$db->query($query);
		
		$query="delete from $met_weixin_reply where weixinid='$id'";
		$db->query($query);
	}
	if($action=="del"){//删除多条数据
		$allidlist=explode(',',$allid);
		foreach($allidlist as $key=>$val){
			$weixinid=$val;
			$access_token_error=1;
			require_once './token.php';
			$met_file="/cgi-bin/menu/delete?access_token=$met_weixin_access_token";
			$post_data=$body;
			$menu=json_decode(curl_post($post_data,30));
			$query = "delete from $met_weixin_accounts where id='$val'";
			$db->query($query);
			
			$query="delete from $met_weixin_reply where weixinid='$val'";
			$db->query($query);			
		}
	}
	metsave('../app/weixin/accounts.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs.'&is_curl=1','',$depth);
}
//获取列表页
$query = "SELECT * FROM $met_weixin_accounts where lang='$lang' ORDER BY id DESC";
$accounts=$db->get_all($query);
foreach($accounts as $key=>$val){
	switch($val[checksuc]){
		case 0:
			$accounts[$key][checksuc1]='开发者凭据输入错误';
		break;
		case 1:
			$accounts[$key][checksuc1]='对接未成功';
		break;
		case 2:
			$accounts[$key][checksuc1]='对接成功';
		break;
	}
	$weixinaccounts.=trim($val[weixinid]).','.trim($val[name])."|";
}
$weixinurl=urlencode($met_weburl);
$weixinaccounts=urlencode($weixinaccounts);
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
//CSS
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
$colspan=7;
//包含模板文件
include template_app('weixin/accounts',$EXT="html");
footer();
?>