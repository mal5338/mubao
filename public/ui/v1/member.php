<!--<?php
# 文件名称
require_once template('head'); 
require_once template('sidebar'); 
echo <<<EOT
-->
        <div id="memberbox">
<!--
EOT;
include templatemember($mfname);
echo <<<EOT
-->
        </div>
<!--
EOT;
require_once template('gap');
require_once template('foot'); 
?>