<?php
$lang->user->tokenInvalid  = "Token 認證失敗，請嘗試使用密碼登錄。";

/* When zentaourl?mode=getconfig is called, the $_SERVER does not have HTTP_USER_AGENT. */
if(isset($_SERVER['HTTP_USER_AGENT']) and strpos($_SERVER['HTTP_USER_AGENT'], 'xuanxuan') != false)
{
    $lang->user->errorDeny = "抱歉，您無權訪問『<b>%s</b>』模組的『<b>%s</b>』功能。請聯繫管理員獲取權限。";
}
