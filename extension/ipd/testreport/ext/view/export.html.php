<?php
/**
 * The export view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php
unset($lang->exportFileTypeList);
if(is_dir($this->app->getCoreLibRoot() . 'word')) $lang->exportFileTypeList['word'] = 'word';
$lang->exportFileTypeList['html'] = 'html';
?>
<style>
body{padding-bottom:0px;}
</style>
<script>
function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        parent.$.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
$(function()
{
    parent.$('.chart-canvas canvas').each(function()
    {
        chartImgData = $(this).get(0).toDataURL("image/png");
        chartID = $(this).attr('id');
        $('#submit').after("<input type='hidden' name='" + chartID +"' id='" + chartID + "' />");
        $('#' + chartID).val(chartImgData);
	});
})
</script>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->export;?></h2>
  </div>
  <form class='main-form' method='post' target='hiddenwin' style='padding: 30px 1%'>
    <table class='w-p100 table-fixed'>
      <tr>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->setFileName;?></span>
            <?php echo html::input('fileName', '', "class='form-control' autocomplete='off'");?>
          </div>
        </td>
        <td class='w-80px'>
          <?php echo html::select('fileType',   $lang->exportFileTypeList, '', 'class="form-control"');?>
        </td>
        <td style='width:70px'>
          <?php echo html::submitButton($lang->export, "onclick='setDownloading();'", 'btn btn-primary');?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
