<?php
//用于效验
class wechatCallbackapi
{
	public function is_valid()
    {
        if($_GET["echostr"]){
			return true;
		}else{
			return false;
		}
    }
	
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    public function responseMsg()
    {
		global $db,$met_weixin_menu,$met_weburl,$met_weixin_info,$met_weixin_attention;
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
				$MsgType = trim($postObj->MsgType);
				$Event   = trim($postObj->Event);
				$EventKey= trim($postObj->EventKey);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";  
				if($MsgType=='event'){
					if($Event=='subscribe'){
						$this->echoMsg('reply',1,$fromUsername,$toUsername);
					}
					if($Event=='CLICK'){
						$id=substr($EventKey,3);
						$this->echoMsg('menu',$id,$fromUsername,$toUsername);
					}
				}			
				if($MsgType=='text'){
					if(!empty( $keyword ))
					{		
						$id=$this->keyword($keyword,$toUsername);
						if($id){
							$this->echoMsg('keywords',$id,$fromUsername,$toUsername);
						}else{
							$this->echoMsg('reply',2,$fromUsername,$toUsername);
						}
					}else{
						echo "Input something...";
					}
				}
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
		global $db,$met_weixin_accounts,$wid;
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			$query="update $met_weixin_accounts set checksuc=2 where originalid='$wid'";
			$weixin=$db->get_one($query);
			return true;
		}else{
			return false;
		}
	}
	private function list_order($listid){
		global $db,$met_column;
		$query="select * from $met_column where id='$listid'";
		$column=$db->get_one($query);
		switch($column[list_order]){
			case '0':
			$list_order=" order by top_ok desc,no_order desc,updatetime desc";
			return $list_order;
			break;

			case '1':
			$list_order=" order by top_ok desc,no_order desc,updatetime desc";
			return $list_order;
			break;

			case '2':
			$list_order=" order by top_ok desc,no_order desc,addtime desc";
			return $list_order;
			break;

			case '3':
			$list_order=" order by top_ok desc,no_order desc,hits desc";
			return $list_order;
			break;

			case '4':
			$list_order=" order by top_ok desc,no_order desc,id desc";
			return $list_order;
			break;

			case '5':
			$list_order=" order by top_ok desc,no_order desc,id";
			return $list_order;
			break;
			
			default :
			$list_order=" order by top_ok desc,no_order desc,updatetime desc";
			return $list_order;
			break;
		}
	}
	private function keyword($keywords,$user){
		global $db,$met_weixin_reply,$met_weixin_accounts,$met_weixin_keywords;
		$query="select * from $met_weixin_accounts where originalid='$user'";
		$weixin=$db->get_one($query);
		$query="select * from $met_weixin_keywords where weixinid='$weixin[id]' order by type ASC,word DESC";
		$words=$db->get_all($query);
		foreach($words as $key=>$val){
			if($val['type']==1){
				if($keywords==$val['word']){
					return $val[replyid];
				}
			}else{
				if(strpos($keywords,$val['word'])!==false){
					return $val[replyid];
				}
			}
		}
	}
	public function echoMsg($type,$id,$fromUsername,$toUsername){
		global $db,$met_weixin_menu,$met_weixin_reply,$met_weburl,$met_weixin_accounts,$met_news,$met_product,$met_download,$met_img,$met_column,$met_wap_ok,$met_wapshowtype;
		$time=time();
		if($type=='menu'){
			$query="select * from $met_weixin_menu where id='$id'";
		}else if($type=='reply'){
			$query="select * from $met_weixin_accounts where originalid='$toUsername'";
			$weixin=$db->get_one($query);
			$query="select * from $met_weixin_reply where weixinid='$weixin[id]' and genre='$id'";
		}else{
			$query="select * from $met_weixin_reply where id='$id'";
		}
		$reply=$db->get_one($query);
		echo "<xml><ToUserName><![CDATA[{$fromUsername}]]></ToUserName><FromUserName><![CDATA[{$toUsername}]]></FromUserName><CreateTime>$time</CreateTime>";
		if($reply[type]==3){
			echo "<MsgType><![CDATA[text]]></MsgType><Content><![CDATA[$reply[text]]]></Content><FuncFlag>0</FuncFlag>";				
		}else if($reply[type]==4){
			$imgtexts=explode('|',$reply['imgtext']);
			foreach($imgtexts as $key=>$val){
				list($imgtext_list[name],$imgtext_list[url],$imgtext_list[img])=explode('*',$val);
				$imgtext_lists[]=$imgtext_list;
			}
			$num=count($imgtext_lists);
			echo "<MsgType><![CDATA[news]]></MsgType><ArticleCount>{$num}</ArticleCount><Articles>";
			foreach($imgtext_lists as $key=>$val){
				echo "<item><Title><![CDATA[{$val[name]}]]></Title>";
				if($i==0){
					echo "<Description><![CDATA[{$reply[description]}]]></Description>";
				}
				if($val[img]){
					$val[img]=substr($val[img],0,7)=='http://'?$val[img]:$met_weburl.str_replace('../','',$val[img]);
					echo "<PicUrl><![CDATA[$val[img]]]></PicUrl>";
				}
				if($val[url]){
					echo "<Url><![CDATA[{$val[url]}]]></Url>";
				}
				echo "</item>";
				$i++;
			}
			echo "</Articles>";
		}else if($reply[type]==5){
			list($mod,$weixinlang,$class1,$class2,$class3)=explode('-',$reply[columns]);
			$table_name='';
			switch($mod){
				case 2:
					$table_name=$met_news;
					$showname='shownews';
				break;
				case 3:
					$table_name=$met_product;
					$showname='showproduct';
				break;
				case 4:
					$table_name=$met_download;
					$showname='showdownload';
				break;
				case 5:
					$table_name=$met_img;
					$showname='showimg';
				break;
			}
			
			$query="select * from {$table_name} where (recycle='0' or recycle='-1') ";
			if($weixinlang){
				$query.=" and lang='$weixinlang' ";
			}
			if($class1){
				$query.=" and class1='$class1' ";
				$cid=$class1;
			}
			if($class2){
				$query.=" and class2='$class2' ";
				$cid=$class2;
			}
			if($class3){
				$query.=" and class3='$class3' ";
				$cid=$class3;
			}				
			if($reply[recommend]){
				$query.=" and com_ok='1' ";
			}
			if($met_wap_ok==1&&$met_wapshowtype==1){
				$query.=" and wap_ok='1' ";
			}else{
				if($met_wapshowtype==1&&!$class1&&!$class2&&!$class3){
					$query.=" and ( 1=0 ";
					$query1="select * from $met_column where lang='$weixinlang' and wap_ok=1 and module='$mod'";
					$columnwaps=$db->get_all($query1);
					foreach($columnwaps as $key=>$val){
						if($val[classtype]==1){
							$columnwaps1[$val[id]]=$val;
						}
						if($val[classtype]==2){
							$columnwaps2[$val[id]]=$val;
						}
						if($val[classtype]==3){
							$columnwaps3[$val[id]]=$val;
						}
					}
					foreach($columnwaps3 as $key=>$val){
						$query.=" or (class3='{$val[id]}' and class2='{$columnwaps2[$val[bigclass]][id]}' and class1='{$columnwaps1[$columnwaps2[$val[bigclass]][bigclass]][id]}') ";
						$columnwaps2[$val[bigclass]][wap_have]=1;
						$columnwaps1[$columnwaps2[$val[bigclass]][bigclass]][wap_have]=1;
					}
					foreach($columnwaps2 as $key=>$val){
						if($val[wap_have]!=1){
							$query.=" or (class2='{$val[id]}' and class1='{$columnwaps1[$val[bigclass]][id]}') ";
							$columnwaps1[$val[bigclass]][wap_have]=1;
						}
					}
					foreach($columnwaps1 as $key=>$val){
						if($val[wap_have]!=1){
							$query.=" or (class1='{$val[id]}') ";
						}
					}
					$query.=" )";
				}
			}
			$query.=$this->list_order($cid)." limit 0,$reply[num]";
			$lists=$db->get_all($query);
			$num=count($lists);
			echo "<MsgType><![CDATA[news]]></MsgType><ArticleCount>$num</ArticleCount><Articles>";
			foreach($lists as $key=>$val){
				$query="select * from $met_column where id='$val[class1]'";
				$column=$db->get_one($query);
				$val[url]=$met_weburl.$column['foldername'].'/'.$showname.'.php?lang='.$val['lang']."&id=".$val['id'];
				echo "<item><Title><![CDATA[{$val[title]}]]></Title>";
				if($i==0&&$val[imgurl]=='')$val[imgurl]=$met_weburl.'public/app/weixin/image/weixin.jpg';
				if($i==0){
					echo "<Description><![CDATA[{$val[description]}]]></Description>";
				}
				if($val[imgurl]){
					$val[imgurl]=substr($val[imgurl],0,7)=='http://'?$val[imgurl]:$met_weburl.str_replace('../','',$val[imgurl]);
					echo "<PicUrl><![CDATA[$val[imgurl]]]></PicUrl>";
				}
				if($val[url]){
					echo "<Url><![CDATA[{$val[url]}]]></Url>";
				}
				echo "</item>";
				$i++;
			}
			echo "</Articles>";
		}
		echo "</xml>";
	}
}

?>