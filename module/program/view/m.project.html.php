<?php
/**
 * The task browse mobile view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2016 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     execution
 * @version     $Id
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
  <?php $refreshUrl = $this->createLink('program', 'project', http_build_query($this->app->getParams()));?>
  <div class='box' data-page='<?php echo $pager->pageID;?>' data-refresh-url='<?php echo $refreshUrl;?>'>
    <table class='table bordered no-margin'>
      <thead>
        <tr>
          <th><?php echo $lang->project->name;?> </th>
          <th class='text-center w-70px'><?php echo $lang->statusAB;?> </th>
          <th class='text-center w-70px'><?php echo $lang->project->progress;?> </th>
        </tr>
      </thead>
      <?php foreach($projectStats as $project):?>
      <tr class='text-center' data-url='<?php echo $this->createLink('project', 'view', "projectID={$project->id}")?>' data-id='<?php echo $project->id;?>'>
        <td class='text-left'><?php echo $project->name;?></td>
        <td class='task-<?php echo $project->status;?>'><?php echo zget($lang->project->statusList, $project->status);?></td>
        <td class='task-left'><?php echo $project->hours->progress . '%';?></td>
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
  $vars = "programID={$program->id}&browseType={$browseType}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
  $sortOrders = array('id', 'name');
  foreach($sortOrders as $sortOrder)
  {
      commonModel::printOrderLink($sortOrder, $orderBy, $vars, '<i class="icon icon-sort-indicator"></i>' . $lang->task->{$sortOrder});
  }
  ?>
</div>

<?php include "../../common/view/m.footer.html.php"; ?>
