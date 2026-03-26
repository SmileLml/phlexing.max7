<?php
/**
 * The riskview of assetlib module of ZenTaoPMS.
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
<?php $canRiskView = (common::hasPriv('risk', 'view') and helper::hasFeature('risk'));?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $risk->id?></span>
      <span class="text" title='<?php echo $risk->name;?>'><?php echo $risk->name;?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->prevention;?></div>
        <div class="detail-content article-content"><?php echo $risk->prevention;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->remedy;?></div>
        <div class="detail-content article-content"><?php echo $risk->remedy;?></div>
      </div>
      <div class="detail">
        <div class="detail-title"><?php echo $lang->risk->resolution;?></div>
        <div class="detail-content article-content"><?php echo $risk->resolution;?></div>
      </div>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=risk&objectID=$risk->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php
        common::printIcon('assetlib', 'editRisk', "riskID=$risk->id", $risk, 'button', 'edit');
        if($risk->status == 'draft') common::printIcon('assetlib', 'approveRisk', "riskID=$risk->id", $risk, 'button', 'glasses', '', 'iframe showinonlybody', true);
        common::printIcon('assetlib', 'removeRisk', "riskID=$risk->id", $risk, 'button', 'unlink', 'hiddenwin');
        ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->risk->legendBasicInfo;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='basicInfo'>
            <table class="table table-data">
              <tbody>
                <?php $widthClass = common::checkNotCN() ? 'w-100px' : 'w-80px';?>
                <tr>
                  <th class='<?php echo $widthClass?>'><?php echo $lang->assetlib->sourceRisk;?></th>
                  <td>
                    <?php if($canRiskView):?>
                    <?php echo html::a($this->createLink('risk', 'view', "riskID={$risk->from}"), $risk->sourceName)?>
                    <?php else:?>
                    <?php echo $risk->sourceName;?>
                    <?php endif;?>
                  </td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->source;?></th>
                  <td><?php echo zget($lang->risk->sourceList, $risk->source);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->category;?></th>
                  <td><?php echo zget($lang->risk->categoryList, $risk->category, $risk->category);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->strategy;?></th>
                  <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->status;?></th>
                  <td><span class='status-story status-<?php echo $risk->status?>'><span class="label label-dot"></span> <?php echo zget($lang->assetlib->statusList, $risk->status);?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->impact;?></th>
                  <td><?php echo zget($lang->risk->impactList, $risk->impact);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->probability;?></th>
                  <td><?php echo zget($lang->risk->probabilityList, $risk->probability);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->rate;?></th>
                  <td><?php echo $risk->rate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->pri;?></th>
                  <td><span class='<?php echo 'pri-' . $risk->pri;?>' title='<?php echo zget($lang->risk->priList, $risk->pri)?>'><?php echo zget($lang->risk->priList, $risk->pri)?></span></td>
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
          <li class='active'><a href='' data-toggle='tab'><?php echo $lang->risk->legendLifeTime;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='legendLifeTime'>
            <table class="table table-data">
              <tbody>
                <tr>
                  <th class='<?php echo $widthClass?>'><?php echo $lang->assetlib->importedBy;?></th>
                  <td><?php echo zget($users, $risk->createdBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->importedDate;?></th>
                  <td><?php echo helper::isZeroDate($risk->createdDate) ?  '' : $risk->createdDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->approvedBy;?></th>
                  <td><?php if($risk->status == 'active') echo zget($users, $risk->assignedTo);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->assetlib->approvedDate;?></th>
                  <td><?php echo helper::isZeroDate($risk->approvedDate) ?  '' : $risk->approvedDate;?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->editedBy;?></th>
                  <td><?php echo zget($users, $risk->editedBy);?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->risk->editedDate;?></th>
                  <td><?php echo helper::isZeroDate($risk->editedDate) ? '' : $risk->editedDate;?></td>
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
