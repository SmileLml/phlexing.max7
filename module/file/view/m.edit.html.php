<?php
/**
 * The edit mobile view file of file module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     file 
 * @version     $Id: index.html.php 3830 2016-05-18 09:34:17Z liugang $
 * @link        http://www.ranzhico.com
 */
?>
<div class='heading divider'>
  <div class='title'><strong><?php echo $lang->file->edit ?></strong></div>
  <nav class='nav'><a data-dismiss='display'><i class='icon-remove muted'></i></a></nav>
</div>

<form class='content box' data-form-refresh='#page' target='hiddenwin' method='post' id='editFileForm' action='<?php echo $this->createLink('file', 'edit', "fileID=$file->id")?>'>
  <div class='control'>
    <?php echo html::input('fileName', $file->title, "class='input'");?>
    <input type="hidden" name="extension" value="<?php echo $file->extension;?>"/>
  </div>

  <div class='footer has-padding'>
    <button type='submit' class='btn primary'><?php echo $lang->save ?></button>
  </div>
</form>
