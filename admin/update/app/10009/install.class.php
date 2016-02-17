<?php

class install {
	public function dosql() {
		global $_M;
		$tablepre = $_M['config']['tablepre'];
		$query = "select * from {$_M['table']['applist']} where no='10009'";
		$stall = DB::get_one($query);
		if(!$stall){
			$property=mysql_get_server_info()>='4.1'?'ENGINE=MyISAM DEFAULT CHARSET=utf8':'TYPE=MyISAM';
			$result_1 = mysql_fetch_row(mysql_query("SHOW TABLES LIKE '{$tablepre}weixin_menu' "));
			if(!$result_1) {
				$query="CREATE TABLE IF NOT EXISTS `{$tablepre}weixin_menu` (
				  `id` int(11) NOT NULL auto_increment,
				  `list_order` int(11) NOT NULL default '0',
				  `name` varchar(50) NOT NULL,
				  `type` varchar(10) NOT NULL,
				  `class` int(11) NOT NULL,
				  `preclass` int(11) NOT NULL,
				  `text` text NOT NULL,
				  `imgtext` text NOT NULL,
				  `description` text NOT NULL,
				  `url` varchar(255) NOT NULL,
				  `columns` text NOT NULL,
				  `recommend` int(1) NOT NULL,
				  `num` int(2) NOT NULL,
				  `weixinid` varchar(255) NOT NULL,
				  `lang` varchar(50) NOT NULL,
				  PRIMARY KEY  (`id`)
				) {$property};";
				DB::query($query);
			}
			$result_2 = mysql_fetch_row(mysql_query("SHOW TABLES LIKE '{$tablepre}weixin_reply' "));
			if(!$result_2) {
				$query="CREATE TABLE IF NOT EXISTS `{$tablepre}weixin_reply` (
				  `id` int(11) NOT NULL auto_increment,
				  `name` varchar(50) NOT NULL,
				  `type` int(1) NOT NULL,
				  `genre` int(1) NOT NULL,
				  `text` text NOT NULL,
				  `imgtext` text NOT NULL,
				  `description` text NOT NULL,
				  `columns` text NOT NULL,
				  `recommend` int(1) NOT NULL,
				  `num` int(2) NOT NULL,
				  `keywords` text NOT NULL,
				  `weixinid` int(11) NOT NULL,
				  `lang` varchar(50) NOT NULL,
				  PRIMARY KEY  (`id`)
				) {$property};";
				DB::query($query);
			}

			$result_3 = mysql_fetch_row(mysql_query("SHOW TABLES LIKE '{$tablepre}weixin_accounts' "));
			if(!$result_3) {
				$query="CREATE TABLE IF NOT EXISTS `{$tablepre}weixin_accounts` (
				  `id` int(11) NOT NULL auto_increment,
				  `name` varchar(50) NOT NULL,
				  `originalid` varchar(50) NOT NULL,
				  `weixinid` varchar(50) NOT NULL,
				  `AppId` varchar(255) NOT NULL,
				  `AppSecret` varchar(255) NOT NULL,
				  `token` varchar(255) NOT NULL,
				  `ticket` varchar(255) NOT NULL,
				  `checksuc` int(1) NOT NULL,
				  `lang` varchar(50) NOT NULL,
				  PRIMARY KEY  (`id`),
				  FULLTEXT KEY `AppId` (`AppId`)
				) {$property};";
				DB::query($query);
			}

			$result_4 = mysql_fetch_row(mysql_query("SHOW TABLES LIKE '{$tablepre}weixin_keywords' "));
			if(!$result_4) {
				$query="CREATE TABLE IF NOT EXISTS `{$tablepre}weixin_keywords` (
				  `id` int(11) NOT NULL auto_increment,
				  `weixinid` int(11) NOT NULL,
				  `replyid` int(11) NOT NULL,
				  `word` varchar(30) NOT NULL,
				  `type` int(1) NOT NULL,
				  PRIMARY KEY  (`id`)
				) {$property};";
				DB::query($query);
			}

			if(!strstr($_M['config']['met_tablename'], "weixin_menu|weixin_reply|weixin_accounts|weixin_keywords")){
				$query="update {$_M['table']['config']} set value=concat(value,'|weixin_menu|weixin_reply|weixin_accounts|weixin_keywords') where name='met_tablename'";
				DB::query($query);
			}
			if(!DB::get_one("select * from {$_M['table']['config']} where name='met_weixin_attention' and lang='metinfo'")){
				$query="INSERT INTO {$_M['table']['config']} set name='met_weixin_attention',value='1',columnid=0,flashid=0,lang='metinfo'";
				DB::query($query);
			}else{
				$query="update {$_M['table']['config']} set value='1' where name='met_weixin_attention'";
				DB::query($query);
			}
			$time = time();
			$query="INSERT INTO {$_M['table']['applist']} set no='10009',ver='1.02',m_name='',m_class='',m_action='',appname='微信公众号管理系统',info='用于网站在移动终端展示的功能',updateime='{$time}'";
			DB::query($query);
			$query="INSERT INTO {$_M['table']['applist']} set no='10009',ver='1.02',m_name='',m_class='',m_action='',appname='微信公众号管理系统',info='用于网站在移动终端展示的功能',updatetime='{$time}'";
			DB::query($query);
			$query = "select * from {$_M['table']['app']} where name='微信公众号管理系统' and download='1'";
			$app_ok = DB::get_one($query);
			if(!$app_ok) {
				$query = "select * from {$_M['table']['app']} where name='微信公众号管理系统' and download=0";
				$app = DB::get_one($query);
				$query = "INSERT INTO {$_M['table']['app']} set name='微信公众号管理系统',no='10009',ver='1.02',img='weixin.jpg',info='用于网站在移动终端展示的功能',file='weixin',power='0',sys='5.2.10',site='1',url='/app/weixin/is_weixin.php',download=1";	
				DB::query($query);
			}
			//return '安装完成';
		}else{
			$query="update {$_M['table']['applist']} set ver='1.02',updateime='{$time}' where no='10009'";
			DB::query($query);
			$query="update {$_M['table']['applist']} set ver='1.02',updatetime='{$time}' where no='10009'";
			DB::query($query);
			$query="update {$_M['table']['app']} set ver='1.02' where no='10009' AND download='1'";
			DB::query($query);
			//return '已经安装';
		}
		return 'complete';
	}
}

?>