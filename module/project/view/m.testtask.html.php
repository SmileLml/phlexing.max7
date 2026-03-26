<?php
/**
 * The browse mobile view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
include "../../common/view/m.header.html.php";
?>
<div class='heading'>
  <div class='title'>
    <a id='sortTrigger' class='text-right sort-trigger' data-display data-target='#sortPanel' data-backdrop='true'><i class='icon icon-sort'></i>&nbsp;<span class='sort-name'><?php echo $lang->sort ?></span></a>
  </div>
</div>

<section id='page' class='section list-with-pager'>
  <?php $refreshUrl = $this->createLink('project', 'testtask', http_build_query($this->app->getParams()));?>
  <div class='box' data-page='<?php echo $pager->pageID ?>' data-refresh-url='<?php echo $refreshUrl ?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th><?php echo $lang->testtask->name;?></th>
          <th class='w-100px'><?php echo $lang->testtask->begin;?></th>
          <th class='w-100px'><?php echo $lang->testtask->end;?></th>
        </tr>
      </thead>
      <?php foreach($tasks as $product => $productTasks):?>
      <?php foreach($productTasks as $task):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('testtask', 'view', "taskID={$task->id}")?>' data-id='<?php echo$task->id;?>'>
        <td class='text-left'><?php echo $task->name;?></td>
        <td><?php echo $task->begin;?></td>
        <td><?php echo $task->end;?></td>
      </tr>
      <?php endforeach;?>
      <?php endforeach;?>
    </table>
  </div>
  <nav class='nav justified pager'>
    <?php $pager->show($align = 'justify');?>
  </nav>
</section>

<div class='list sort-panel hidden affix enter-from-bottom layer' id='sortPanel'>
  <?php $vars = "productID=$productID&branch=$branch&type=$scope,$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <?php
  $sortOrders = array('id', 'name', 'begin', 'end', 'owner', 'status', 'build');
  foreach ($sortOrders as $order)
  {
      commonModel::printOrderLink($order, $orderBy, $vars, '<i class="icon icon-sort-indicator"></i>' . ($lang->testtask->{$order}));
  }
  ?>
</div>
<?php include "../../common/view/m.footer.html.php"; ?>
