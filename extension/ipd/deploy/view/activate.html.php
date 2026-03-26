<?php
/**
 * The activate view file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $deploy->name . $lang->hyphen . $lang->deploy->activate;?></h2>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-80px'><?php echo $lang->deploy->lblBeginEnd;?></th>
        <td class='w-p40'>
          <div class='input-group'>
            <?php echo html::input('begin', substr($deploy->begin, 0, 16), "class='form-control form-datetime' placeholder='{$lang->deploy->begin}'");?>
            <span class='input-group-addon fix-border'>~</span>
            <?php echo html::input('end', substr($deploy->end, 0, 16), "class='form-control form-datetime' placeholder='{$lang->deploy->end}'");?>
          </div>
        </td><td></td>
      </tr>
      <tr>
        <th><?php echo $lang->comment?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "class='form-control'")?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
