<?php
/**
 * The browse view file of dimension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenxuan Song <1097180981@qq.com>
 * @package     dimension
 * @version     $Id: edit.html.php 4129 2022-11-1 14:18:32Z $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->dimension->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='thWidth'><?php echo $lang->dimension->name;?></th>
          <td class='w-400px'>
            <?php echo html::input('name', $dimension->name, "class='form-control'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->dimension->code;?></th>
          <td>
            <?php echo html::input('code', $dimension->code, "class='form-control' placeholder='{$lang->dimension->codeHolder}'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->dimension->desc;?></th>
          <td><?php echo html::textarea('desc', $dimension->desc, "rows='7' class='form-control'");?></td>
        </tr>
        <tr>
          <td></td>
          <td class='form-actions'>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
