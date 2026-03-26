<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('screen', $screen);?>
<?php js::set('scopeList', $scopeList);?>
<?php js::set('backLink', $backLink);?>
<style>
html, body, #main .container{width: 100% !important; height: 100% !important;}
#main {width: 100% !important; height: calc(100% - 50px) !important;}
#main .container {margin:0; padding: 0; max-width:100% !important}
#main iframe {vertical-align: bottom;}
</style>
<iframe id="screenContainer" width="100%" height="100%" scrolling="no" frameborder='0' marginheight="0" marginwidth="0" src="<?php echo $this->createLink('screen', 'design', 'screenID=' . $screen->id . '&page=detail');?>"></iframe>
<?php common::printLink('screen', 'publish', 'screenID=' . $screen->id, '', '', " id='publish' class='iframe' data-width='600' style='display: none;'", '', true);?>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
