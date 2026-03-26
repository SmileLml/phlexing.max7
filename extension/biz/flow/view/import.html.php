<?php
/**
 * The import view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='ajaxForm' enctype='multipart/form-data' action='<?php echo $this->createLink($module, 'import');?>'>
  <table class='table table-form'>
    <tr>
      <th class='w-80px'><?php echo $lang->flow->importMode;?></th>
      <td class='w-200px'><?php echo html::select('mode', $lang->flow->importModeList, 'auto', "class='form-control chosen'");?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td colspan='2'>
        <?php foreach($lang->flow->importModeList as $key => $label):?>
        <span class='tips text-important <?php echo $key;?>Mode'><?php echo $lang->flow->tips->importMode[$key];?></span>
        <?php endforeach;?>
      </td>
    </tr>
    <tr>
      <th><?php echo $lang->files;?></th>
      <td colspan='2'><?php echo html::file('files');?></td>
    </tr>
    <tr>
      <th></th>
      <td colspan='2' class='form-actions'><?php echo html::submitButton($lang->import);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
