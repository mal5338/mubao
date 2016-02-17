<?php
$depth='../';
require_once $depth.'../login/login_check.php';
/////////////////
if($action=="modify"){
$re='';
	if($class1==-2){
		$list[0]=array("select id from $met_column where module='1' and lang='$lang'",'1');
		$list[1]=array("select id from $met_news where lang='$lang'",'2');
		$list[2]=array("select id from $met_product where lang='$lang'",'3');
		$list[3]=array("select id from $met_download where lang='$lang'",'4');
		$list[4]=array("select id from $met_img where lang='$lang'",'5');
		foreach($list as $listkey=>$listval){
			$rearray=$db->get_all($listval[0]);
			foreach($rearray as $key=>$val){
				$re.=$val['id'].'-'.$listval[1].'|';
			}
		}

	}
	else{
		$class=$class3?$class3:($class2?$class2:$class1);
		$remark=$db->get_one("select * from $met_column where id='$class'");
		$table=moduledb($remark['module']);
		if($remark['module']!='1'){
			$resql="class1='$class1'";
			$resql.=$class2?" and class2='$class2'":"";
			$resql.=$class3?" and class3='$class3'":"";
			$renow=$db->get_all("select * from $table where $resql and (recycle='0' or recycle='-1')");
			$re='';
			foreach($renow as $key=>$val){
				$re.=$val['id'].'-'.$remark['module'].'|';
			}
		}else{
			if($remark[classtype]!=3){
				$classnext=$db->get_all("select * from $met_column where bigclass='$remark[id]' and module=1");
				foreach($classnext as $key=>$val){
					$re.=$val['id'].'-'.'1|';
				}
				if($remark[classtype]==1){
					foreach($classnext as $key1=>$val1){
						$classnext2=$db->get_all("select * from $met_column where bigclass='$val1[id]' and module=1");
						foreach($classnext2 as $key2=>$val2){
							$re.=$val2['id'].'-'.'1|';
						}
					}
				}
			}
			$re.=$class.'-'.'1|';
		}	
	}
echo $re;
die();
}
/*
$action="modifyid";
$table='met_product';
$id=17;
$retexted='localhost';
$retext='192.168.1.103';
*/
if($action=="modifyid"){
	if($id){
		$num=$table;
		$table=moduledb($table);
		$renow=$db->get_one("select * from $table where id='$id'");
		$content[0]=$renow[content];
		$content[1]=$renow[content1];
		$content[2]=$renow[content2];
		$content[3]=$renow[content3];
		$content[4]=$renow[content4];
		$updateflag=0;
		foreach($content as $contentkey=>$contentval){
			if($contentval){
				$tmp1 = explode("<",$contentval);
				$i=0;
				$flag=0;
				foreach($tmp1 as $key=>$val){
					$tmp2=explode(">",$val);
					switch($type){
						case 1:
							if(strcasecmp(substr(trim($tmp2[0]),0,3),'img')==0){
								$tmp2[0]=str_ireplace($retexted,$retext,$tmp2[0]);
								$tmp1[$i]=implode(">",$tmp2);
								$flag=1;
							}
						break;
						case 2:
							if(strcasecmp(substr(trim($tmp2[0]),0,1),'a')==0){
								$tmp2[0]=str_ireplace($retexted,$retext,$tmp2[0]);
								$tmp1[$i]=implode(">",$tmp2);
								$flag=1;
							}
						break;
						case 3:
							if($tmp2[1])$tmp2[1]=str_ireplace($retexted,$retext,$tmp2[1]);
							$tmp1[$i]=implode(">",$tmp2);
							$flag=1;
						break;
					}
					$i++;
				}
				if($flag==1){
					$updateflag=1;
					$content[$contentkey]=implode("<",$tmp1);
				}
			}
		}
		if($updateflag){
			if($num==3||$num==5)$sql=",content1='$content[1]',content2='$content[2]',content3='$content[3]',content4='$content[4]'";
			$query="update $table set content='$content[0]'$sql where id='$id'";
			echo $query;
			$db->query($query);
			
		}
	}
die();
}
$query = "SELECT * FROM $met_column where (module=1 or module=2 or module=3 or module=4 or module=5) and lang='$lang'";
$result = $db->query($query);
while($list = $db->fetch_array($result)) {
$clist[]=$list;
if($list['classtype']==1||$list['releclass']){$clist1now[]=$list;}
}
$i=0;
$listjs = "<script language = 'JavaScript'>\n";
$listjs.= "var onecount;\n";
$listjs.= "lev = new Array();\n";
foreach($clist as $key=>$vallist){
	if($vallist['releclass']){
		$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".$vallist[access]."');\n";
		$i=$i+1;
	}
	else{
			$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
			$i=$i+1;
	}
}
$listjs.= "onecount=".$i.";\n";
$listjs.= "</script>";
//////////////////
$jiathis_lang1[$lang]="checked='checked'";
$met_jiathis_ok1[$met_jiathis_ok]="checked='checked'";
$met_tools_ok1[$met_tools_ok]="checked='checked'";
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template_app('replace/index',$EXT="html");
footer();
?>