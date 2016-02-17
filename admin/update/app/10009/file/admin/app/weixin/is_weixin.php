<?php
# MetInfo Enterprise Content Management System 
# Copyright (C) MetInfo Co.,Ltd (http://www.metinfo.cn). All rights reserved. 
	if(strpos($m_user_agent, 'MicroMessenger') !== false){
		$weixin=1;
	}else{
		$weixin=0;
	}	
	$query="select * from $met_app where no='10009' and download=1";
	if($weixin==1&&$met_wap==1&&!$db->get_one($query)){
		$ulrs=explode('?',$REQUEST_URI);
		$selfs=explode('/',$ulrs[0]);
		switch($selfs[count($selfs)-1]){
			case 'shownews.php':
				$wap_module=2;
			break;
			case 'showproduct.php':
				$wap_module=3;
			break;
			case 'showdownload.php':
				$wap_module=4;
			break;
			case 'showimg.php':
				$wap_module=5;
			break;
			default :
				$wap_module=0;
			break;
		}
		if($wap_module){
			Header("Location: ../wap/index.php?".$ulrs[1].'&module='.$wap_module);
		}
	}

# This program is an open source system, commercial use, please consciously to purchase commercial license.
# Copyright (C) MetInfo Co., Ltd. (http://www.metinfo.cn). All rights reserved.
?>