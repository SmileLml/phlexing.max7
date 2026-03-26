<?php
/**
 * The opportunityview of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php $canOpportunityView = (common::hasPriv('opportunity', 'view') and helper::hasFeature('opportunity'));?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $opportunity->id?></span>
      <span class="text" title='<?php echo $opportunity->name;?>'><?php echo $opportunity->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->desc;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->desc;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->prevention;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->prevention;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->opportunity->resolution;?></div>
        <div class="detail-content article-content"><?php echo $opportunity->resolution;?></div>
      </div>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=opportunity&objectID=$opportunity->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php
        common::printIcon('assetlib', 'editOpportunity', "opportunityID=$opportunity->id", $opportunity, 'button', 'edit');
        if($opportunity->status == 'draft') common::printIcon('assetlib', 'approveOpportunity', "opportunityID=$opportunity->id", $opportunity, 'button', 'glasses', '', 'iframe showinonlybody', true);
        common::printIcon('assetlib', 'removeOpportunity', "opportunityID=$opportunity->id", $opportunity, 'button', 'unlink', 'hiddenwin');
        ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->opportunity->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <?php $widthClass = common::checkNotCN() ? 'w-120px' : 'w-80px';?>
                <tr>
                  <th class='<?php echo $widthClass?>'><?php echo $lang->assetlib->sourceOpportunity;?></th>
                  <td>
                    <?php if($canOpportunityView):?>
                    <?php echo html::a($this->createLink('opportunity', 'view', "opportunityID={$opportunity->from}"), $opportunity->sourceName);?>
                    <?php else:?>
                    <?php echo $opportunity->sourceName;?>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->source;?></th>
                  <td><?php echo zget($lang->opportunity->sourceList, $opportunity->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->type;?></th>
                  <td><?php echo zget($lang->opportunity->typeList, $opportunity->type, $opportunity->type);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->strategy;?></th>
                  <td><?php echo zget($lang->opportunity->strategyList, $opportunity->strategy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->status;?></th>
                  <td><span class='status-story status-<?php echo $opportunity->status?>'><span class="label label-dot"></span> <?php echo zget($lang->assetlib->statusList, $opportunity->status);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->impact;?></th>
                  <td><?php echo zget($lang->opportunity->impactList, $opportunity->impact);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->chance;?></th>
                  <td><?php echo zget($lang->opportunity->chanceList, $opportunity->chance);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->ratio;?></th>
                  <td><?php echo $opportunity->ratio;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->pri;?></th>
                  <td><span class='<?php echo 'pri-' . $opportunity->pri;?>' title='<?php echo zget($lang->opportunity->priList, $opportunity->pri)?>'><?php echo zget($lang->opportunity->priList, $opportunity->pri)?></span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->opportunity->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <?php $widthClass = common::checkNotCN() ? 'w-100px' : 'w-80px';?>
                <tr>
                  <th class='<?php echo $widthClass;?>'><?php echo $lang->assetlib->importedBy;?></th>
                  <td><?php echo zget($users, $opportunity->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->importedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->createdDate) ?  '' : $opportunity->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->approvedBy;?></th>
                  <td><?php if($opportunity->status == 'active') echo zget($users, $opportunity->assignedTo);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->approvedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->approvedDate) ?  '' : $opportunity->approvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->editedBy;?></th>
                  <td><?php echo zget($users, $opportunity->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->opportunity->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($opportunity->editedDate) ? '' : $opportunity->editedDate;?></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
