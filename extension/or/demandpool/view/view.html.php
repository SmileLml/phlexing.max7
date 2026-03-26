<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a(inlink('browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $demandpool->id?></span>
      <span class="text" title='<?php echo $demandpool->name;?>'><?php echo $demandpool->name;?></span>
      <?php if($demandpool->deleted):?>
      <span class='label label-danger'><?php echo $lang->demandpool->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->demandpool->desc;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($demandpool->desc) ? $demandpool->desc : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $demandpool->files, 'fieldset' => 'true', 'object' => $demandpool));?>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=demandpool&objectID=$demandpool->id");?>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack(inlink('browse'));?>
        <div class='divider'></div>
        <?php
          if($demandpool->status == 'normal') common::printIcon('demandpool', 'close', "demandpoolID=$demandpool->id", $demandpool, 'button', 'off', '', 'iframe', true);
          if($demandpool->status == 'closed') common::printIcon('demandpool', 'activate', "demandpoolID=$demandpool->id", $demandpool, 'button', 'magic', '', 'iframe', true);
          common::printIcon('demandpool', 'edit', "demandpoolID=$demandpool->id", $demandpool, 'button');
          common::printIcon('demandpool', 'delete', "demandpoolID=$demandpool->id", $demandpool, 'button', 'trash', 'hiddenwin');
        ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->demandpool->basicInfo;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tbody>
              <tr>
                <th class='w-100px'><?php echo $lang->demandpool->owner;?></th>
                <td><?php foreach(explode(',', $demandpool->owner) as $user) echo zget($users, $user, '') . ' ';?></td>
              </tr>
              <tr>
                <th class='w-100px'><?php echo $lang->demandpool->reviewer;?></th>
                <td><?php foreach(explode(',', $demandpool->reviewer) as $user) echo zget($users, $user, '') . ' ';?></td>
              </tr>
              <tr>
                <th><?php echo $lang->demandpool->status;?></th>
                <td><?php echo zget($lang->demandpool->statusList, $demandpool->status, '');?></td>
              </tr>
              <tr>
                <th class='bindproduct'><?php echo $lang->demandpool->products;?></th>
                <td>
                    <?php
                          $productList = '';
                          foreach(explode(',', $demandpool->products) as $product) $productList .= "<p>" . zget($products, $product, '') . "</p>";
                          echo $productList;
                    ?>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->demandpool->acl;?></th>
                <td><?php echo zget($lang->demandpool->aclList, $demandpool->acl, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->demandpool->createdBy;?></th>
                <td><?php echo zget($users, $demandpool->createdBy, '');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->demandpool->createdDate;?></th>
                <td><?php echo $demandpool->createdDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
