<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.fix.html.php';?>
<?php js::set('unfoldStories', array());?>
<?php js::set('poolID', $poolID);?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $moreMenu = array();
    foreach($lang->demand->labelList as $label => $labelName)
    {
        $active = $browseType == $label ? 'btn-active-text' : '';
        echo html::a($this->createLink('demand', 'browse', "poolID=$poolID&browseType=$label&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"), '<span class="text">' . $labelName . '</span>' . ($browseType == $label ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link $active'");
    }

    /* More drop menu. */
    echo "<div class='btn-group' id='more'>";
    $current = $lang->demand->more;
    $active  = '';
    if(isset($lang->demand->moreSelects[$browseType]))
    {
        $current = "<span class='text'>{$lang->demand->moreSelects[$browseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        $active  = 'btn-active-text';
    }
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
    echo "<ul class='dropdown-menu'>";
    foreach($lang->demand->moreSelects as $key => $value)
    {
        if($key == '') continue;
        echo '<li' . ($key == $browseType ? " class='active'" : '') . '>';
        echo html::a($this->createLink('demand', 'browse', "poolID=$poolID&type=$key"), $value);
    }
    echo '</ul></div>';
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->searchAB;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu" id='exportActionMenu'>
        <?php
        $class = common::hasPriv('demand', 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv('demand', 'export') ? "data-toggle='modal' data-size='sm' class='export'" : "class=disabled";
        $link  = common::hasPriv('demand', 'export') ? $this->createLink('demand', 'export', "poolID=$poolID&orderBy=$orderBy") : '#';
        echo "<li $class>" . html::a($link, $lang->demand->export, '', $misc) . "</li>";

        $class = common::hasPriv('demand', 'exportTemplate') ? '' : "class='disabled'";
        $link  = common::hasPriv('demand', 'exportTemplate') ? $this->createLink('demand', 'exportTemplate', "poolID=$poolID") : '#';
        $misc  = common::hasPriv('demand', 'exportTemplate') ? "data-toggle='modal' data-type='iframe' data-width='650px' class='exportTemplate'" : "class='disabled'";
        echo "<li $class>" . html::a($link, $lang->demand->exportTemplate, '', $misc) . '</li>';
        ?>
      </ul>
      <?php if(common::hasPriv('demand', 'import')) echo html::a($this->createLink('demand', 'import', "poolID=$poolID"), '<i class="icon-import muted"></i> <span class="text">' . $lang->demand->import . '</span>', '', "class='btn btn-link import' data-toggle='modal' data-type='iframe'");?>
    </div>
    <div class='btn-group dropdown'>
      <?php
      $createLink      = $this->createLink('demand', 'create', "poolID=$poolID");
      $batchCreateLink = $this->createLink('demand', 'batchCreate', "poolID=$poolID");
      $buttonLink  = '';
      $buttonTitle = '';
      if(common::hasPriv('demand', 'batchCreate'))
      {
          $buttonLink  = $batchCreateLink;
          $buttonTitle = $lang->demand->batchCreate;
      }

      if(common::hasPriv('demand', 'create'))
      {
          $buttonLink  = $createLink;
          $buttonTitle = $lang->demand->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn btn-primary $hidden create-demand-btn'");
      ?>

      <?php if(!empty($poolID) and common::hasPriv('demand', 'batchCreate') and common::hasPriv('demand', 'create')): ?>
      <button type='button' class="btn btn-primary dropdown-toggle" data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($createLink, $lang->demand->create);?> </li>
        <li><?php echo html::a($batchCreateLink, $lang->demand->batchCreate);?></li>
      </ul>
      <?php endif;?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='demand' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=demand');?>"></div>
    <?php if(empty($demands)):?>
    <div class="table-empty-tip">
      <p><span class="text-muted"><?php echo $lang->noData;?></span></p>
    </div>
    <?php else:?>
    <?php
    $vars = "poolID=$poolID&browseType=$browseType&param=0&orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";

    $datatableId  = $this->moduleName . ucfirst($this->methodName);

    $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');

    if($useDatatable) include $app->getModuleRoot() . 'common/view/datatable.html.php';

    $setting = $this->datatable->getOldSetting('demand');
    $widths  = $this->datatable->setFixedFieldWidth($setting);
    $columns = 0;
    ?>
    <form class='main-table' method='post' id='demandForm' <?php if(!$useDatatable) echo "data-ride='table'";?>>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
    <table class='table has-sort-head <?php if($useDatatable) echo ' datatable';?>' id='demandList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php
          foreach($setting as $value)
          {
              if($value->show)
              {
                  if($value->id == 'BSA')
                  {
                      $fixed = $value->fixed == 'no' ? 'true' : 'false';
                      echo "<th data-flex='$fixed' data-width='$value->width' style='width:$value->width' class='c-$value->id' title='{$lang->demand->bsaTip}'>";
                      common::printOrderLink($value->id, $orderBy, $vars, $value->title);
                      echo "</th>";
                      continue;
                  }

                  $this->datatable->printHead($value, $orderBy, $vars);
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($demands as $demand):?>
          <tr data-id='<?php echo $demand->id?>' <?php if(!empty($demand->children)) echo "data-children=" . count($demand->children);?>>
            <?php foreach($setting as $value) $this->demand->printCell($value, $demand, $users, $useDatatable ? 'datatable' : 'table');?>
          </tr>
          <?php if(!empty($demand->children)):?>
          <?php $i = 0;?>
          <?php foreach($demand->children as $key => $child):?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($demand->children)) ? ' table-child-bottom' : '';?>
          <tr class='table-children<?php echo $class;?> parent-<?php echo $demand->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>'>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
            foreach($setting as $key => $value)
            {
                if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
                {
                    $this->demand->printCell($value, $child, $users, $useDatatable ? 'datatable' : 'table');
                }
            }?>
            <?php else:?>
            <?php foreach($setting as $key => $value) $this->demand->printCell($value, $child, $users, $useDatatable ? 'datatable' : 'table');?>
            <?php endif;?>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class="table-footer">
        <?php if(common::hasPriv('demand', 'export')):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$('#toStoryBtn').on('click', function()
{
    var demandID = $('#demand').val();
    var type = $("input[name*='totype']:checked").val();
    var link = createLink('demand', 'tostory', 'demandID=' + demandID + '&type=' + type);
    location.href = link;
})

function getDemandID(obj)
{
    var demandID = $(obj).attr("data-id");
    $('#demand').val(demandID);
}
</script>
<?php if(!empty($useDatatable)):?>
<script>
$(function(){$('#demandForm').table();})
</script>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
