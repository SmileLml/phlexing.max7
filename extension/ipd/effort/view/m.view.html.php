<?php
/**
 * The view mobile view file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
if($this->session->effortList)
{
    $browseLink = $this->session->effortList;
}
elseif($effort->account == $app->user->account)
{
    $browseLink = $this->createLink('my', 'effort');
}
else
{
    $browseLink = $this->createLink('user', 'effort', "userID=$user->id");
}
$bodyClass  = 'with-nav-bottom';
include "../../common/view/m.header.html.php";
?>

<div id='page' class='list-with-actions'>
  <div class='heading'>
    <div class='title'><?php echo $lang->effort->view;?></div>
    <nav class='nav'>
      <a href='javascript:history.go(-1);' class='btn primary'><?php echo $lang->goback;?></a>
    </nav>
  </div>
  <div class='section no-margin'>
    <div class='box'>
      <table class='table bordered table-detail'>
        <tr>
          <th class='w-80px'><?php echo $lang->effort->account;?></th>
          <td><?php echo zget($users, $effort->account);?></td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->date;?></th>
          <td><?php echo date(DT_DATE1, strtotime($effort->date));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->consumed;?></th>
          <td>
            <?php
            if(isset($effort->consumed)) echo $effort->consumed . ' ' . $lang->effort->hour;
            ?>
          </td>
        </tr>
        <?php if($effort->objectType == 'task'):?>
        <tr>
          <th><?php echo $lang->effort->left;?></th>
          <td>
            <?php
            if(isset($effort->consumed)) echo $effort->left . ' ' . $lang->effort->hour;
            ?>
          </td>
        </tr>
        <?php endif;?>
        <tr <?php if($work) echo "data-url='" . $this->createLink($effort->objectType, 'view', "objectID={$effort->objectID}") . "'"?>>
          <th><?php echo $lang->effort->objectType;?></th>
          <td>
            <?php
            echo $lang->effort->objectTypeList[$effort->objectType];
            if($work) echo ' #' . $effort->objectID . ' ' .$work[$effort->objectID];
            ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->effort->work;?></th>
          <td><?php echo $effort->work;?></td>
        </tr>
      </table>
    </div>
    <?php include '../../common/view/m.action.html.php';?>
  </div>


  <nav class='nav nav-auto affix dock-bottom footer-actions'>
  <?php
  if($effort->account == $app->user->account)
  {
      common::printIcon('effort', 'edit',   "effortID=$effort->id", $effort, 'button','', '', '', false, "data-display='modal' data-placement='bottom'", $lang->edit);
      if(common::hasPriv('effort', 'delete')) echo html::a($this->createLink('effort', 'delete', "effortID=$effort->id"), $lang->delete, 'hiddenwin');
  }
  ?>
  </nav>
</div>
<?php include "../../common/view/m.footer.html.php"; ?>
