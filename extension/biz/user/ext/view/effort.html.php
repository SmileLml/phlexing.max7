<?php
/**
 * The control file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $this->app->getModuleRoot() . 'user/view/featurebar.html.php';?>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      foreach($lang->user->featureBar['effort'] as $key => $name)
      {
          echo "<li id='$key'>" . html::a(inlink('effort', "userID={$user->id}&date=$key"), $name) . '</li>';
      }
      ?>
      <script>$('#<?php echo $type;?>').addClass('active')</script>
    </ul>
  </nav>
</div>
<form class='main-table table-effort'>
  <table class='table table-fixed'>
    <thead>
      <tr class='colhead'>
        <th class='w-60px'><?php echo $lang->idAB;?></th>
        <th class='w-date'><?php echo $lang->effort->date;?></th>
        <th class='w-60px'><?php echo $lang->effort->consumed;?></th>
        <th width='350'><?php echo $lang->effort->objectType;?></th>
        <th><?php echo $lang->effort->work;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $times = 0?>
      <?php foreach($efforts as $effort):?>
      <tr class='text-center'>
        <td class='text-left'>
          <?php printf('%03d', $effort->id);?>
        </td>
        <td><?php echo $effort->date;?></td>
        <td class='text-left'><?php echo $effort->consumed;?></td>
        <td class='text-left'><?php if($effort->objectType != 'custom') echo html::a($this->createLink($effort->objectType, 'view', "id=$effort->objectID", '', true), $effort->objectTitle, '', "class='iframe'");?></td>
        <td class='text-left'><?php echo html::a($this->createLink('effort', 'view', "id=$effort->id&from=my"), $effort->work);?></td>
      </tr>
      <?php $times += $effort->consumed;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php if($efforts):?>
  <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</form>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
