<?php
$this->app->loadLang('reviewcl');
$this->app->loadLang('reviewsetting');
js::set('methodName', $this->app->methodName);
$browsMethod   = $type == 'waterfall' ? 'browse' : 'waterfallplusBrowse';
$versionMethod = $type == 'waterfall' ? 'version' : 'waterfallplusVersion';
$browseLang    = $type == 'waterfall' ? $lang->reviewcl->browse : $lang->reviewcl->waterfallplusBrowse;
$versionLang   = $type == 'waterfall' ? $lang->reviewsetting->version : $lang->reviewsetting->waterfallplusVersion;
?>
<?php if(common::hasPriv('reviewcl', 'browse')) echo html::a($this->createLink('reviewcl', $browsMethod, "type=$type&object=$object"), '<span class="text">' . $browseLang . '</span>', '', "class='btn btn-link browseTab'");?>
<?php if(common::hasPriv('reviewsetting', 'version')) echo html::a($this->createLink('reviewsetting', $versionMethod, "type=$type&object=$object"), '<span class="text">' . $versionLang. '</span>', '', "class='btn btn-link versionTab'");?>
<script>
$('#mainMenu .' + methodName + 'Tab').addClass('btn-active-text');
</script>
