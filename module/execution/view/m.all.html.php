<?php
/**
 * The all mobile view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     execution
 * @version     $Id
 * @link        http://www.zentao.net
 */

$bodyClass = 'with-menu-top';
include "../../common/view/m.header.html.php";
?>
<nav id='menu' class='menu nav affix dock-top canvas'>
  <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=undone&projectID=$projectID"),    $lang->execution->undone, '', "id='undoneTab'");?>
  <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=all&projectID=$projectID"),       $lang->execution->all, '', "id='allTab'");?>
  <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=wait&projectID=$projectID"),      $lang->execution->statusList['wait'], '', "id='waitTab'");?>
  <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=doing&projectID=$projectID"),     $lang->execution->statusList['doing'], '', "id='doingTab'");?>
  <?php echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, "status=suspended&projectID=$projectID"), $lang->execution->statusList['suspended'], '', "id='suspendedTab'");?>
  <a class='moreMenu hidden' data-display='dropdown' data-placement='beside-bottom'><?php echo $lang->more;?></a>
  <div id='moreMenu' class='list dropdown-menu'></div>
</nav>

<div class='heading'>
  <div class='title'>
    <a id='sortTrigger' class='text-right sort-trigger' data-display data-target='#sortPanel' data-backdrop='true'><i class='icon icon-sort'></i>&nbsp;<span class='sort-name'><?php echo $lang->sort ?></span></a>
  </div>
</div>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('execution', 'all', "status=$status&projectID=$projectID&orderBy=$orderBy&productID=$productID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}");?>
  <div class='box' data-page='<?php echo $pager->pageID;?>' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th><?php echo $lang->execution->name;?> </th>
          <th class='text-center w-70px'><?php echo $lang->statusAB;?> </th>
          <th class='text-center w-80px'><?php echo $lang->execution->progress;?> </th>
        </tr>
      </thead>
      <?php foreach($executionStats as $execution):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('execution', 'view', "executionID={$execution->id}")?>' data-id='<?php echo $execution->id;?>'>
        <td class='text-left'><?php echo $execution->name;?></td>
        <?php if(isset($execution->delay)):?>
        <td><?php echo $lang->execution->delayed;?></td>
        <?php else:?>
        <td><?php echo $lang->execution->statusList[$execution->status];?></td>
        <?php endif;?>
        <td><?php echo $execution->hours->progress;?>%</td>
      </tr>
      <?php endforeach;?>
    </table>
  </div>

  <nav class='nav justify pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>

<div class='list sort-panel hidden affix enter-from-bottom layer' id='sortPanel'>
  <?php
  $vars = "status={$status}&executionID={$executionID}&orderBy=%s&productID={$productID}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
  $sortOrders = array('id', 'name', 'begin', 'end', 'PM', 'status', 'order');
  foreach($sortOrders as $sortOrder)
  {
      commonModel::printOrderLink($sortOrder, $orderBy, $vars, '<i class="icon icon-sort-indicator"></i>' . ($sortOrder == 'id' ? $lang->idAB : $lang->execution->{$sortOrder}));
  }
  ?>
</div>

<script>$("#<?php echo $status;?>Tab").addClass('active');</script>

<?php include "../../common/view/m.footer.html.php"; ?>
