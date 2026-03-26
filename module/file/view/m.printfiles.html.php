<?php
/**
 * The print files mobile view file of file module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     file
 * @version     $Id: buildform.html.php 7417 2013-12-23 07:51:50Z wwccss $
 * @link        http://www.ranzhico.com
 */

if(empty($files)) return;
$sessionString = session_name() . '=' . session_id();
?>
<?php if($fieldset == 'true'):?>
<?php $filesUID = 'files-' . uniqid(); ?>
<div class='heading gray'>
  <div class='title'><strong><?php echo $lang->file->common;?></strong></div>
</div>
<div class='list files' id='#<?php echo $filesUID ?>'>
<?php endif;?>
  <?php foreach ($files as $file):?>
  <div class='item item-file' data-file-id='<?php echo $file->id ?>'>
    <div class='content'>
        <span class='text-link'><?php echo (preg_match("/\.{$file->extension}$/", $file->title)) ? $file->title : $file->title . '.' . $file->extension;?></span>
        <span class='muted small'>
          <?php
          /* Show size info. */
          if($file->size < 1024)
          {
              echo "<span>(" . $file->size . 'B' . ")</span>";
          }
          elseif($file->size < 1024 * 1024)
          {
              $file->size = round($file->size / 1024, 2);
              echo "<span>(" . $file->size . 'K' . ")</span>";
          }
          elseif($file->size < 1024 * 1024 * 1024)
          {
              $file->size = round($file->size / (1024 * 1024), 2);
              echo "<span>(" . $file->size . 'M' . ")</span>";
          }
          else
          {
              $file->size = round($file->size / (1024 * 1024 * 1024), 2);
              echo "<span>(" . $file->size . 'G' . ")</span>";
          }
          ?>
        </span>
    </div>
    <?php common::printLink('file', 'download', "fileID=$file->id", "<i class='icon icon-download'></i>", '_blank', "class='fileAction btn btn-link text-primary' title='{$lang->file->downloadFile}'");?>
    <a class='btn edit-file' data-stop-propagation='true' data-placement='bottom' data-display='modal' data-remote='<?php echo $this->createLink('file', 'edit', "fileID=$file->id") ?>'><i class='icon-pencil text-link'></i></a>
    <a class='btn delete-file text-danger' href='<?php echo $this->createLink('file', 'delete', "fileID=$file->id") ?>' target='hiddenwin'><i class='icon-trash'></i></a>
  </div>
  <?php endforeach;?>
<?php if($fieldset == 'true'):?>
</div>
<script>
$(function()
{
    var $files = $('#<?php echo $filesUID ?>');
    if($files.parent().hasClass('modal'))
    {
        $files.listenScroll({container: 'parent'}).prev('.heading').addClass('divider').find('.nav').append('<a data-dismiss="display"><i class="icon icon-remove muted"></i></a>');
    }
});
</script>
<?php endif;?>
<script>
$(function()
{
    var $body = $('body');
    if(!$body.data('bindDownloadFile'))
    {
        $body.on($.TapName, '.item-file[data-file-id] > .avatar, .item-file[data-file-id] > .content', function()
        {
            <?php
            $downloadLink  = $this->createLink('file', 'download', "fileID={0}");
            $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
            $downloadLink .= $sessionString;
            ?>
            var url = '<?php echo $downloadLink;?>';
            window.open($.format(url, $(this).parent().data('fileId')), '_blank');
        }).data('bindDownloadFile', true);
    }
});
</script>
