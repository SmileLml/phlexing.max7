<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $browseLink = $app->session->demandList ? $app->session->demandList : inlink('browse', "poolID=$demand->pool");?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $demand->id?></span>
      <span class="text" title='<?php echo $demand->title;?>' style='color: <?php echo $demand->color;?>'>
        <?php if($demand->parent > 0) echo '<span class="label label-badge label-primary no-margin">' . $lang->demand->childrenAB . '</span>';?>
        <?php if($demand->parent > 0) echo isset($demand->parentName) ? html::a(inlink('view', "demandID={$demand->parent}&version=0", '', true), $demand->parentName, '', isOnlybody() ? '' : "class='iframe' data-width='90%'") . ' / ' : '';?><?php echo $demand->title;?>
      </span>
      <?php if($demand->version > 1):?>
      <small class='dropdown'>
        <a href='#' data-toggle='dropdown' class='text-muted'><?php echo '#' . $version;?> <span class='caret'></span></a>
        <ul class='dropdown-menu'>
        <?php
        for($i = $demand->version; $i >= 1; $i --)
        {
            $class = $i == $version ? " class='active'" : '';
            echo '<li' . $class .'>' . html::a(inlink('view', "demandID=$demand->id&version=$i"), '#' . $i) . '</li>';
        }
        ?>
        </ul>
      </small>
      <?php endif; ?>
      <?php if($demand->deleted):?>
        <span class='label label-danger'><?php echo $lang->demand->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->demand->spec;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($demand->spec) ? $demand->spec: "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->demand->verify;?></div>
        <div class="detail-content article-content">
          <?php echo !empty($demand->verify) ? $demand->verify : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $demand->files, 'fieldset' => 'true', 'object' => $demand));?>
      <?php if(!empty($demand->children)):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->demand->children;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed'>
            <thead>
              <tr class='text-center'>
                <th class='w-50px'> <?php echo $lang->demand->id;?></th>
                <th class='w-40px' title=<?php echo $lang->demand->pri;?>><?php echo $lang->priAB;?></th>
                <th><?php echo $lang->demand->title;?></th>
                <th class='w-100px'><?php echo $lang->demand->assignedTo;?></th>
                <th class='w-80px'> <?php echo $lang->demand->status;?></th>
                <th class='w-230px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($demand->children as $child):?>
              <tr class='text-center'>
                <td><?php echo $child->id;?></td>
                <td>
                  <?php
                  $priClass = $child->pri ? 'label-pri label-pri-' . $child->pri : '';
                  echo "<span class='$priClass'>";
                  echo $child->pri == '0' ? '' : zget($this->lang->demand->priList, $child->pri, $child->pri);
                  echo "</span>";
                  ?>
                </td>
                <td class='text-left' title='<?php echo $child->title;?>'><a <?php echo isOnlybody() ? '' : 'class="iframe"';?> data-width="90%" href="<?php echo $this->createLink('demand', 'view', "demandID=$child->id", '', true); ?>"><?php echo $child->title;?></a></td>
                <td><?php echo zget($users, $child->assignedTo, '');?></td>
                <td><?php echo $this->processStatus('demand', $child);?></td>
                <td class='c-actions'>
                  <?php
                  echo $this->demand->buildOperateMenu($child, 'browse');
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php if($demand->stories):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $this->lang->demand->distributedStory;?></div>
        <div class='detail-content article-content'>
          <table class='table table-hover table-fixed'>
            <thead>
              <tr>
                <th class='w-60px'> <?php echo $lang->demand->id;?></th>
                <th class='w-200px'><?php echo $lang->demand->product;?></th>
                <th><?php echo $lang->demand->title;?></th>
                <th class='w-80px'> <?php echo $lang->demand->status;?></th>
                <th> <?php echo $lang->demand->roadmapAB;?></th>
                <th class='w-100px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($demand->stories as $storyID => $story):?>
              <tr class='text-left'>
                <td><?php echo $storyID?></td>
                <td title="<?php echo zget($products, $story->product, '');?>">
                  <?php echo zget($products, $story->product, '');?>
                </td>
                <td title='<?php echo $story->title;?>'><?php echo html::a($this->createLink('story', 'view', "id={$story->id}&version=0&param=0&storyType=requirement", '', true), $story->title, 'hiddenwin', 'class="iframe"');?></td>
                <td><?php echo zget($lang->story->statusList, $story->status);?></td>
                <td title='<?php echo zget($roadmaps, $story->roadmap, '');?>'><?php echo zget($roadmaps, $story->roadmap, '');?></td>
                <td class='c-actions'>
                  <?php echo common::hasPriv('demand', 'retract') && empty($story->retractedBy) ? html::a($this->createLink('demand', 'retract', "storyID=$storyID", '', true), $lang->demand->retract, 'hiddenwin', 'class="iframe" data-width="90%"') : '';?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif;?>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=demand&objectID=$demand->id");?>
    </div>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$demand->deleted) echo $this->demand->buildOperateMenu($demand, 'view');?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#basicInfo' data-toggle='tab'><?php echo $lang->demand->basicInfo;?></a></li>
          <li><a href='#lifeTime' data-toggle='tab'><?php echo $lang->demand->lifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->demand->pool;?></th>
                  <td class='c-pool' title='<?php echo zget($demandpools, $demand->pool, '');?>'><?php echo zget($demandpools, $demand->pool, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->status;?></th>
                  <td><?php echo zget($lang->demand->statusList, $demand->status, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->product;?></th>
                  <td>
                  <?php
                  $productList = '';
                  foreach(explode(',', $demand->product) as $product) $productList .= zget($products, $product) . ',';
                  echo trim($productList, ',') ? trim($productList, ',') : $lang->demand->undetermined;
                  ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->pri;?></th>
                  <td>
                    <?php if($demand->pri):?>
                    <span class='label-pri <?php echo 'label-pri-' . $demand->pri;?>' title='<?php echo zget($lang->demand->priList, $demand->pri);?>'><?php echo zget($lang->demand->priList, $demand->pri)?></span>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->category;?></th>
                  <td><?php echo zget($lang->demand->categoryList, $demand->category);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->source;?></th>
                  <td><?php echo zget($lang->demand->sourceList, $demand->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->sourceNote;?></th>
                  <td><?php echo $demand->sourceNote;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->BSA;?></th>
                  <td><?php echo zget($lang->demand->bsaList, $demand->BSA);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->duration;?></th>
                  <td><?php echo zget($lang->demand->durationList, $demand->duration);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->feedbackedBy;?></th>
                  <td><?php echo $demand->feedbackedBy;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->email;?></th>
                  <td><?php echo $demand->email;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->keywords;?></th>
                  <td><?php echo $demand->keywords;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->mailto;?></th>
                  <td><?php if($demand->mailto) foreach(explode(',', $demand->mailto) as $user) echo zget($users, $user, '') . ' '; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='lifeTime'>
            <table class='table table-data'>
              <tbody>
                <tr>
                  <th class='w-100px'><?php echo $lang->demand->createdBy;?></th>
                  <td><?php echo zget($users, $demand->createdBy, '') . $lang->at . $demand->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->assignedTo;?></th>
                  <td><?php echo zget($users, $demand->assignedTo, '') . $lang->at . (helper::isZeroDate($demand->assignedDate) ? '' : $demand->assignedDate);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->reviewer;?></th>
                  <td>
                    <?php
                    if(!empty($demand->reviewer))
                    {
                        foreach($demand->reviewer as $reviewer) echo zget($users, $reviewer, '') . ' ';
                    }
                    ?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->reviewedDate;?></th>
                  <td><?php echo helper::isZeroDate($demand->reviewedDate) ? '' : $demand->reviewedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->closedBy;?></th>
                  <td><?php echo zget($users, $demand->closedBy, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->closedReason;?></th>
                  <td><?php echo zget($lang->demand->reasonList, $demand->closedReason, '');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->demand->lastEditedBy;?></th>
                  <td><?php echo zget($users, $demand->lastEditedBy, '') . $lang->at . helper::isZeroDate($demand->lastEditedDate) ? '' : $demand->lastEditedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="todemand">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <h4 class="modal-title"><?php echo $lang->demand->chooseType;?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->demand->demandType;?></th>
            <td><?php echo html::radio('totype', $lang->demand->demandTypeList, 'demand');?></td>
            <td class='text-center'>
              <?php echo html::commonButton($lang->demand->next, "id='todemandBtn'", 'btn btn-primary');?>
              <?php echo html::hidden('demand', '');?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>

<div id="mainActions" class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<script>
$('#todemandBtn').on('click', function()
{
    var demandID = $('#demand').val();
    var type = $("input[name*='totype']:checked").val();
    var link = createLink('demand', 'todemand', 'demandID=' + demandID + '&type=' + type);
    location.href = link;
})

function getRequirementID(obj)
{
    var demandID = $(obj).attr("data-id");
    $('#demand').val(demandID);
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
