<?php
/**
 * The importfromlib view file of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     issue
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('projectID', $projectID);?>
<?php js::set('from', $from);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->issueList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectLib;?></span>
      <?php echo html::select('fromlib', $libraries, $libID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=importIssue');?>" data-module='importIssue' class='show cell'></div>
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
          <th class="w-80px"><?php echo $lang->issue->type;?></th>
          <th class="w-auto"><?php echo $lang->issue->title;?></th>
          <th class="w-80px"><?php echo $lang->issue->severity;?></th>
          <th class="w-60px"><?php echo $lang->issue->pri;?></th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0;?>
      <?php foreach($issues as $issue):?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='issueIdList[<?php echo $issue->id?>]' value='<?php echo $issue->id;?>' />
            <label></label>
          </div>
          <?php printf('%03d', $issue->id);?>
        </td>
        <td title="<?php echo zget($lang->issue->typeList, $issue->type);?>"><?php echo zget($lang->issue->typeList, $issue->type);?></td>
        <td class="text-ellipsis" title="<?php echo $issue->title;?>"><?php echo html::a($this->createLink('assetlib', 'issueView', "issueID=$issue->id", '', true), $issue->title, '', "class='iframe' data-width='70%'");?></td>
        <td title="<?php echo zget($lang->issue->severityList, $issue->severity);?>"><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
        <td title="<?php echo $issue->pri;?>"><span class="label-pri label-pri-<?php echo $issue->pri?>"><?php echo $issue->pri;?></span></td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($issues):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll;?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->issue->import, '', 'btn btn-secondary');?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
