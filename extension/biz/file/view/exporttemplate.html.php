<?php
/**
 * The export template view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     file
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<script>
function setDownloading()
{
    if(/opera/.test(navigator.userAgent.toLowerCase())) return true;   // Opera don't support, omit it.

    $.cookie('downloading', 0);
    time = setInterval("closeWindow()", 300);
    return true;
}

function closeWindow()
{
    if($.cookie('downloading') == 1)
    {
        parent.$.zui.closeModal();
        $.cookie('downloading', null);
        clearInterval(time);
    }
}
<?php if(!class_exists('ZipArchive')):?>
$().ready(function()
{
    $('#fileType').change(function()
    {
        if($(this).val() == 'xlsx')
        {
            $(this).val('xls');
            $('#phpZipNotice').html(lang.installZipExtension).show().fadeOut(10000);
        }
    });
});
<?php endif;?>
</script>
<style>
#submit {height: auto;}
</style>
<form method='post' target='hiddenwin' onsubmit='setDownloading();' style='padding: 30px 5% 30px' action='<?php echo $app->getURI();?>'>
  <table class='w-p100'>
    <tr>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->setFileNum;?></span>
          <?php echo html::input('num', '10', 'class=form-control');?>
          <span class='input-group-addon '><?php echo $lang->setFileType;?></span>
          <?php echo html::select('fileType', array('xls' => 'xls', 'xlsx' => 'xlsx'), 'xlsx', "class='form-control'");?>
        </div>
      </td>
      <td><?php echo html::submitButton($lang->export);?></td>
    </tr>
    <tr>
      <td>
        <?php if(!class_exists('ZipArchive')):?> <div class='text-danger' id="phpZipNotice"></div> <?php endif;?>
      </td>
    </tr>
  </table>
</form>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
