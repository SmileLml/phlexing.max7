<?php
/**
 * The importrisk view file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
    <?php echo html::a($this->session->riskList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectProject;?></span>
      <?php echo html::select('fromProject', $allProject, $projectID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=assetRisk');?>" data-module='assetRisk' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importRisk' data-ride='table'>
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
          <th class='w-120px'><?php common::printOrderLink('rate',   $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-pri'>  <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
          <th>                <?php common::printOrderLink('name', $orderBy, $vars, $lang->assetlib->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
          <th class='w-150px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='w-200px'><?php common::printOrderLink('project', $orderBy, $vars, $lang->assetlib->project);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($risks as $risk):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='riskIdList[<?php echo $risk->id?>]' value='<?php echo $risk->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $risk->id);?>
          </td>
          <td><?php echo $risk->rate;?></td>
          <td><span class='<?php echo 'pri-' . $risk->pri;?>' title='<?php echo zget($lang->risk->priList, $risk->pri, $risk->pri);?>'><?php echo $risk->pri == '0' ? '' : zget($lang->risk->priList, $risk->pri, $risk->pri);?></span></td>
          <td class='text-left nobr c-name' title="<?php echo $risk->name;?>"><?php if(!common::printLink('risk', 'view', "riskID=$risk->id", $risk->name, '', "class='iframe'", true, true)) echo $risk->name;?></td>
          <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
          <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
          <td class="c-name" title="<?php echo $allProject[$risk->project];?>"><?php echo $allProject[$risk->project];?></td>
        </tr>
        <?php endforeach;?>
        <?php echo html::hidden('lib', $libID);?>
      </tbody>
    </table>
    <?php if($risks):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->assetlib->import, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::linkButton($lang->goback, $this->session->riskList);?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
