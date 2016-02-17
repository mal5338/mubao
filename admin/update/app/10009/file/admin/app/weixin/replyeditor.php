<?php
$depth='../';
require_once $depth.'../login/login_check.php';
require_once './export.func.php';

$query="select * from $met_weixin_reply where id='$id'";
$reply=$db->get_one($query);
list($mod,$weixinlang,$class1,$class2,$class3)=explode('-',$reply[columns]);
$mod=$mod?$mod:2;
$selected_mod[$mod]="selected='selected'";
$selected_lang[$weixinlang]="selected='selected'";
$selected_class1[$class1]="selected='selected'";
$selected_class2[$class2]="selected='selected'";
$selected_class3[$class3]="selected='selected'";
if(!$reply[recommend])$reply[recommend]=0;
$recommend1[$reply[recommend]]="checked='checked'";
$reply['type']=$reply['type']?$reply['type']:3;
switch($reply['type']){
	case 1:
		$displaytext='style="display:none"';
		$dispalyimgtext='style="display:none"';
		$dispalyurl='style="display:none"';
		$displaycontents='style="display:none"';
	break;
	case 2:
		$displaytext='style="display:none"';
		$dispalyimgtext='style="display:none"';
		$dispalyurl="";
		$displaycontents='style="display:none"';
	break;
	case 3:
		$displaytext="";
		$dispalyimgtext='style="display:none"';
		$dispalyurl='style="display:none"';
		$displaycontents='style="display:none"';
	break;
	case 4:
		$displaytext='style="display:none"';
		$dispalyimgtext="";
		$dispalyurl='style="display:none"';
		$displaycontents='style="display:none"';
	break;	
	case 5:
		$displaytext='style="display:none"';
		$dispalyimgtext='style="display:none"';
		$dispalyurl='style="display:none"';
		$displaycontents='';
	break;	
}
$imgtexts=explode('|',$reply['imgtext']);
foreach($imgtexts as $key=>$val){
	list($imgtext_list[name],$imgtext_list[url],$imgtext_list[img])=explode('*',$val);
	$imgtext_lists[]=$imgtext_list;
	$imgtext_list[img]=substr($imgtext_list[img],0,7)=='http://'?$imgtext_list[img]:$met_weburl.str_replace('../','',$imgtext_list[img]);
}
$wenxin_reply_type1[$reply['type']]="selected='selected'";
$imgnum=count($imgtext_lists);
$imgnum=$imgnum?$imgnum:1;

if($met_wapshowtype==1)$sql=' and wap_ok=1';
$query = "SELECT * FROM $met_column where (module=2 or module=3 or module=4 or module=5) and lang='$lang' $sql";
$result = $db->query($query);
while($list = $db->fetch_array($result)) {
$clist[]=$list;
if(($list['classtype']==1||$list['releclass'])&&$mod==$list[module]){$clist1now[]=$list;}
if((($list['classtype']==2&&$list['bigclass']==$class1)||($met_class[$list['bigclass']]['releclass']&&$list['classtype']==3&&$list['bigclass']==$class1))&&$mod==$list[module]){$clist2now[]=$list;}
if(($list['classtype']==3&&$list['bigclass']==$class2)&&$mod==$list[module]){$clist3now[]=$list;}
}
$i=0;
$listjs = "<script language = 'JavaScript'>\n";
$listjs.= "var onecount;\n";
$listjs.= "lev = new Array();\n";
foreach($clist as $key=>$vallist){
	if($vallist['releclass']){
		$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".'0'."','".$vallist[module]."','".$vallist[lang]."');\n";
		$i=$i+1;
	}
	else{
			$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".'0'."','".$vallist[module]."','".$vallist[lang]."');\n";
			$i=$i+1;
	}
}
$listjs.= "onecount=".$i.";\n";
$listjs.= "</script>";
if(!$reply[num])$reply[num]=10;
$query="select * from $met_weixin_keywords where replyid='$reply[id]'";
$keywords=$db->get_all($query);
foreach($keywords as $key=>$val){
	if($val[type]==1){
		$keywords_all.=$val[word].'|';
	}else{
		$keywords_contain.=$val[word].'|';
	}
}
$keywords_all=trim($keywords_all,'|');
$keywords_contain=trim($keywords_contain,'|');
$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template_app('weixin/replyeditor',$EXT="html");
footer();
?>