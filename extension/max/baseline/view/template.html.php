<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-row fade">
  <div id='tableList' class='main-col main-content main-table'>
    <div id="listHead">
      <div class='pull-left'>
        <strong><?php echo $lang->baseline->docTemplate;?></strong>
      </div>
      <div class="btn-toolbar pull-right">
      <?php common::printLink('baseline', 'createTemplate', "", "<i class='icon icon-plus'></i>" . $lang->baseline->createTemplate, '', "class='btn btn-primary'");?>
      </div>
    </div>
    <form class='main-table' method='post' id='templateForm'>
      <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
      <table class='table has-sort-head'>
        <thead>
          <tr>
            <th class='w-50px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?></th>
            <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->baseline->name);?></th>
            <th class='w-140px'><?php common::printOrderLink('templateType', $orderBy, $vars, $lang->baseline->templateType);?></th>
            <th class='w-80px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->baseline->docType);?></th>
            <th class='w-120px'><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->baseline->addedBy);?></th>
            <th class='w-150px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->baseline->addedDate);?></th>
            <th class='c-actions-4'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($templates as $template):?>
          <tr>
            <td><?php echo $template->id;?></td>
            <td class='c-title text-ellipsis'><?php echo html::a(helper::createLink('baseline', 'view', "templateID=$template->id"), $template->title);?></td>
            <td><?php echo zget($lang->baseline->objectList, $template->templateType);?></td>
            <td><?php echo zget($lang->baseline->docTypeList, $template->type);?></td>
            <td><?php echo zget($users, $template->addedBy);?></td>
            <td><?php echo substr($template->addedDate, 0, 10);?></td>
            <td class='c-actions'>
              <?php common::printIcon('baseline', 'manageBook', "templateID=$template->id", $template, 'list', 'cog', '', '', '', '', $lang->baseline->manageBook);?>
              <?php common::printIcon('baseline', 'editTemplate', "templateID=$template->id", $template, 'list', 'edit', '', '', '', '', $lang->edit);?>
              <?php common::printIcon('baseline', 'delete', "templateID=$template->id&confirm=no", $template, 'list', 'trash', 'hiddenwin');?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
