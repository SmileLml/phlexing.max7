<?php
/**
 * The collabora view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style>
body{padding-bottom:0px;}
</style>
<?php if($isEditable and isset($collaboraEdit)):?>
<?php echo html::a($collaboraEdit, "<i class='icon icon-edit' title='{$lang->edit}'></i> {$lang->edit}", 'collabora', "id='collaboraEdit' style='position:absolute;top:11px;right:35px;z-index:9999;'");?>
<?php endif;?>
<iframe allow="clipboard-write" src='<?php echo $collaboraUrl;?>' border='0' scrolling="no" allowfullscreen style='width:100%;height:100%;display:block;position:absolute;top:0;z-index:60;' id='collabora' name='collabora'></iframe>
<script>
$(function()
{
    $('#collaboraEdit').click(function(){$(this).hide();});
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
