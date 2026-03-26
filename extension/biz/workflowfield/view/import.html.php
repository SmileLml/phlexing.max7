<?php
/**
 * The import view file of workflowfield module of ZDOO.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     workflowfield
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<form method='post' id='importForm' enctype='multipart/form-data' action='<?php echo inlink('import', "module=$module&type=$type");?>'>
  <table class='table table-form'>
    <tr>
      <td><?php echo html::file('files');?></td>
    </tr>
    <tr>
      <td class='form-actions'><?php echo html::submitButton($lang->import);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.modal.html.php';?>
