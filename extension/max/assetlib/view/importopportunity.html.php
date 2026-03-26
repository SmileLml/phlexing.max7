<?php
/**
 * The importopportunity view file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     assetlib
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('libID', $libID);?>
<?php js::set('projectID', $projectID);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->opportunityList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectProject;?></span>
      <?php echo html::select('fromProject', $allProject, $projectID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=assetOpportunity');?>" data-module='assetOpportunity' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importOpportunity' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "libID=$libID&projectID=$projectID&orderBy=%s&browseType=$browseType&queryID=$queryID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-120px'><?php common::printOrderLink('ratio',   $orderBy, $vars, $lang->opportunity->ratio);?></th>
          <th class='w-pri'>  <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
          <th>                <?php common::printOrderLink('name', $orderBy, $vars, $lang->assetlib->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->opportunity->type);?></th>
          <th class='w-150px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->opportunity->strategy);?></th>
          <th class='w-200px'><?php common::printOrderLink('project', $orderBy, $vars, $lang->assetlib->project);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($opportunities as $opportunity):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='opportunityIdList[<?php echo $opportunity->id?>]' value='<?php echo $opportunity->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $opportunity->id);?>
          </td>
          <td><?php echo $opportunity->ratio;?></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $opportunity->pri;?>' title='<?php echo zget($lang->opportunity->priList, $opportunity->pri, $opportunity->pri);?>'><?php echo $opportunity->pri == '0' ? '' : zget($lang->opportunity->priList, $opportunity->pri, $opportunity->pri);?></span></td>
          <td class='text-left nobr c-name' title="<?php echo $opportunity->name;?>"><?php if(!common::printLink('opportunity', 'view', "opportunityID=$opportunity->id", $opportunity->name, '', "class='iframe'", true, true)) echo $opportunity->name;?></td>
          <td><?php echo zget($lang->opportunity->typeList, $opportunity->type);?></td>
          <td><?php echo zget($lang->opportunity->strategyList, $opportunity->strategy);?></td>
          <td class="c-name" title="<?php echo $allProject[$opportunity->project];?>"><?php echo $allProject[$opportunity->project];?></td>
        </tr>
        <?php endforeach;?>
        <?php echo html::hidden('lib', $libID);?>
      </tbody>
    </table>
    <?php if($opportunities):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->assetlib->import, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::linkButton($lang->goback, $this->session->opportunityList);?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
