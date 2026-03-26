<?php
/**
 * The risk view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuchun Li <liyuchun@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: risk.html.php 4771 2021-01-13 14:18:02Z $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/tablesorter.html.php';?>
<?php include $app->getModuleRoot() . 'user/view/featurebar.html.php';?>
<style>
.pri-low {color: #313C52;}
.pri-middle {color: #FF9F46;}
.pri-high {color: #FB2B2B;}
</style>
<div id='mainContent'>
  <nav id='contentNav'>
    <ul class='nav nav-default'>
      <?php
      foreach($lang->user->featureBar['risk'] as $key => $name)
      {
          $active = $key == $type ? 'active' : '';
          echo "<li class='$active'>" . html::a(inlink('risk', "userID={$user->id}&type=$key"), $name) . "</li>";
      }
      ?>
    </ul>
  </nav>

  <div class='main-table'>
    <table class="table has-sort-head table-fixed" id='risktable'>
      <?php $vars = "userID={$user->id}&type=$type&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID"; ?>
      <thead>
        <tr>
          <th class='text-left w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->risk->id);?></th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='c-pri text-center'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->priAB);?></th>
          <th class='w-80px text-center'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-80px'><?php common::printOrderLink('status', $orderBy, $vars, $lang->risk->status);?></th>
          <th class='w-120px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
          <th class='w-120px'><?php common::printOrderLink('identifiedDate', $orderBy, $vars, $lang->risk->identifiedDate);?></th>
          <th class='w-80px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($risks as $risk):?>
        <tr>
          <td><?php echo $risk->id;?></td>
          <td><?php echo html::a($this->createLink('risk', 'view', "riskID=$risk->id"), $risk->name, '', "data-app='project'");?></td>
          <td class='text-center'><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
          <td class='text-center'><?php echo $risk->rate;?></td>
          <td class="status-risk status-<?php echo $risk->status;?>"><?php echo zget($lang->risk->statusList, $risk->status);?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
          <td><?php echo $risk->identifiedDate == '0000-00-00' ? '' : $risk->identifiedDate;?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($risks):?>
    <div class="table-footer"><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
