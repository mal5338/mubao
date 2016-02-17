<?php
$depth='../';
require_once $depth.'../login/login_check.php';
if($action=='imgadd'){
	$num = $lp+1-1;
    $newlist = "
		<div class=\"v52fmbx_dlbox newlist caidantype_4\">
		<h3 class=\"v52fmbx_hr metsliding\" sliding=\"1\">图文内容[{$num}]</h3>
			<dl>
				<dt>图片{$lang_marks}</dt>
				<dd style='position:relative;'>
					<input name='displayimg{$lp}' type='text' class='text' value='' />
					<input name='met_upsql{$lp}' type='file' id='displayimg_upload{$lp}' />
					<script type='text/javascript'>
						metuploadify('#displayimg_upload{$lp}','upimage','displayimg{$lp}','','3');
					</script>
					<a href='javascript:;' onclick='imgnumfu();deletdisplayimg($(this));' class='displayimg_del'>{$lang_delete}</a>
				</dd>
			</dl>
			<dl>
				<dt>标题{$lang_marks}</dt>
				<dd style='position:relative;'>
				<input name='displayname{$lp}' type='text' class='text nonull' value='' />
				</dd>
			</dl>
			<dl>
				<dt>链接{$lang_marks}</dt>
				<dd style='position:relative;'>
				<input name='displayurl{$lp}' type='text' class='text nonull' value='' />
				<span class='tips'>一般用于链接到手机网站指定页面</span>
				</dd>
			</dl>
		</div>
			";
	echo $newlist;
	die();
}
$preclass=$preclass?$preclass:0;
$query = "SELECT * FROM $met_weixin_menu where weixinid='$weixinid' ORDER BY list_order ASC";
$result=$db->query($query);
while($list= $db->fetch_array($result)){
	if($list['class']==1){
		$list_array1[]=$list;
	}else{
		$list_array2[$list['preclass']][]=$list;
	}
}
if(count($list_array1)>=3&&$level==1&&$action=='add'){
	metsave('../app/weixin/index.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs.'&id='.$weixinid,'一级菜单只能添加3个',$depth);
}
if(count($list_array2[$preclass])>=5&&$action=='add'){
	metsave('../app/weixin/index.php?lang='.$lang.'&anyid='.$anyid.'&cs='.$cs.'&id='.$weixinid,'二级菜单只能添加5个',$depth);
}
if(count($list_array2[$id])>0&&$action=='editor'&&$level==1){
	$disable_weixin_menu_type="disabled='disabled'";
	$disable_weixin_menu_tips="<span class='tips'>此一级栏目含有下级菜单，类型只能选择菜单类型。</span><input type='hidden' name='weixin_menu_type' value='1'/>";
}
$query = "SELECT * FROM $met_weixin_menu where id='$id'";
$menu=$db->get_one($query);
if(!$menu['preclass'])$display_local_pre='style="display:none"';
list($mod,$weixinlang,$class1,$class2,$class3)=explode('-',$menu[columns]);
$mod=$mod?$mod:2;
$selected_mod[$mod]="selected='selected'";
$selected_lang[$weixinlang]="selected='selected'";
$selected_class1[$class1]="selected='selected'";
$selected_class2[$class2]="selected='selected'";
$selected_class3[$class3]="selected='selected'";
$recommend1[$menu[recommend]]="checked='checked'";
if(!$menu[recommend])$recommend1[0]="checked='checked'";
if(!$menu[num])$menu[num]=10;
$menu['type']=$menu['type']?$menu['type']:($level==1?1:2);
switch($menu['type']){
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

$imgtexts=explode('|',$menu['imgtext']);
foreach($imgtexts as $key=>$val){
	list($imgtext_list[name],$imgtext_list[url],$imgtext_list[img])=explode('*',$val);
	$imgtext_lists[]=$imgtext_list;
	$imgtext_list[img]=substr($imgtext_list[img],0,7)=='http://'?$imgtext_list[img]:$met_weburl.str_replace('../','',$imgtext_list[img]);
}
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

$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$met_shop_cash1[$met_shop_cash]="checked='checked'";
$met_shop_invoice1[$met_shop_invoice]="checked='checked'";
$weixin_menu_local_pre1[$menu['preclass']]="selected='selected'";
$weixin_menu_local_level1[$menu['class']]="selected='selected'";
$weixin_menu_type1[$menu['type']]="selected='selected'";

$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template_app('weixin/indexeditor',$EXT="html");
footer();
?>