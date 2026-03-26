<?php
/**
 * The importfromlib view file of risk module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     risk
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<?php js::set('from', $from);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->riskList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectLib;?></span>
      <?php echo html::select('fromlib', $libraries, $libID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=importRisk');?>" data-module='importRisk' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importFromLib' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "projectID=$projectID&from=$from&libID=$libID&orderBy=%s&browseType=$browseType&queryID=$queryID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->risk->name);?></th>
          <th class='w-100px'><?php common::printOrderLink('strategy', $orderBy, $vars, $lang->risk->strategy);?></th>
          <th class='w-100px'><?php common::printOrderLink('rate', $orderBy, $vars, $lang->risk->rate);?></th>
          <th class='w-100px'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->risk->pri);?></th>
          <th class='w-120px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->risk->category);?></th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0;?>
      <?php foreach($risks as $risk):?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='riskIdList[<?php echo $risk->id?>]' value='<?php echo $risk->id;?>' />
            <label></label>
          </div>
          <?php printf('%03d', $risk->id);?>
        </td>
        <td class='c-name' title=<?php echo $risk->name;?>><?php echo html::a($this->createLink('assetlib', 'riskView', "riskID=$risk->id", '', true), $risk->name, '', "class='iframe' data-width='70%'");?></td>
        <td><?php echo zget($lang->risk->strategyList, $risk->strategy);?></td>
        <td><?php echo $risk->rate;?></td>
        <td><?php echo "<span class='pri-{$risk->pri}'>" . zget($lang->risk->priList, $risk->pri) . "</span>";?></td>
        <td><?php echo zget($lang->risk->categoryList, $risk->category);?></td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($risks):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll;?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->risk->import, '', 'btn btn-secondary');?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
