<?php
$depth='../';
require_once $depth.'../login/login_check.php';

$cs=isset($cs)?$cs:1;
$listclass[$cs]='class="now"';
$css_url=$depth."../templates/".$met_skin."/css";
$img_url=$depth."../templates/".$met_skin."/images";
include template_app('weixin/help',$EXT="html");
footer();
?>