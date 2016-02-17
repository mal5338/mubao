<?php

class install {
	public function dosql() {
		global $_M;
		$query = "select * from {$_M['table']['applist']} where no='10004'";
		$stall = DB::get_one($query);
		if(!$stall){
			//return '安装完成';
			$time = time();
			$query="INSERT INTO {$_M['table']['applist']} set no='10004',ver='1.0',m_name='',m_class='',m_action='',appname='内容批量替换器',info='能够批量替换内容文字、超链接、图片路径，如公司地址、电话、某个链接地址变更，逐个修改效率太低，此应用是很好的解决办法。',updateime='{$time}'";
			DB::query($query);
			$query="INSERT INTO {$_M['table']['applist']} set no='10004',ver='1.0',m_name='',m_class='',m_action='',appname='内容批量替换器',info='能够批量替换内容文字、超链接、图片路径，如公司地址、电话、某个链接地址变更，逐个修改效率太低，此应用是很好的解决办法。',updatetime='{$time}'";
			DB::query($query);
			$query = "select * from {$_M['table']['app']} where name='内容批量替换器' and download='1'";
			$app_ok = DB::get_one($query);
			if(!$app_ok) {
				$query = "INSERT INTO {$_M['table']['app']} set name='内容批量替换器',no='10004',ver='1.0',img='8.jpg',info='能够批量替换内容文字、超链接、图片路径，如公司地址、电话、某个链接地址变更，逐个修改效率太低，此应用是很好的解决办法。',file='replace',power='0',sys='5.2.10',site='0',url='',download=1";	
				DB::query($query);
			}
		}else{
			//return '已经安装';
		}
		return 'complete';
	}
}

?>