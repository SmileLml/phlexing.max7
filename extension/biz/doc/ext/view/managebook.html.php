<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$spliter = (empty($this->app->user->feedback) && !$this->cookie->feedbackView && $app->tab == 'doc') ? true : false;
if(!$spliter) $browseType = '';
?>
<div class="main-row fade" id="mainRow">
  <?php include $app->getModuleRoot() . 'doc/view/side.html.php';?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-book text-muted"></i>
          <?php echo $book->name;?>
        </div>
      </div>
      <nav class="panel-actions btn-toolbar">
        <div class="dropdown">
          <button class="btn" type="button" data-toggle="dropdown"><i class='icon-cog'></i> <span class="caret"></span></button>
          <ul class='dropdown-menu'>
            <li><?php common::printLink('doc', 'catalog', "libID={$book->id}&nodeID=0", "<i class='icon icon-plus'></i>" . $lang->doc->catalog);?></li>
            <li><?php common::printLink('doc', 'deleteLib',  "libID=$book->id&confirm=no&type=book", "<i class='icon icon-trash'></i>" . $lang->delete, 'hiddenwin');?></li>
          </ul>
        </div>
      </nav>
      <div class='panel-body'>
        <?php if(empty($catalog)):?>
        <div class="table-empty-tip">
          <p>
            <span class="text-muted"><?php echo $lang->doc->addCatalogTip;?></span>
            <?php echo html::a(helper::createLink('doc', 'catalog', "bookID={$book->id}&nodeID=0"), "<i class='icon icon-plus'></i>" . $lang->doc->catalog, '',"class='btn btn-info'");?>
          <p>
        </div>
        <?php else:?>
        <div class='books'><?php echo $catalog;?></div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
