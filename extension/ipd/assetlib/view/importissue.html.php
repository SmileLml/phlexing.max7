<?php
/**
 * The importissue view file of assetlib module of ZenTaoPMS.
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
    <?php echo html::a($this->session->issueList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectProject;?></span>
      <?php echo html::select('fromProject', $allProject, $projectID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=assetIssue');?>" data-module='assetIssue' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importIssue' data-ride='table'>
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
          <th class='w-120px'><?php common::printOrderLink('severity',   $orderBy, $vars, $lang->issue->severity);?></th>
          <th class='w-pri'>  <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
          <th>                <?php common::printOrderLink('title', $orderBy, $vars, $lang->assetlib->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('type', $orderBy, $vars, $lang->issue->type);?></th>
          <th class='w-200px'><?php common::printOrderLink('project', $orderBy, $vars, $lang->assetlib->project);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($issues as $issue):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='issueIdList[<?php echo $issue->id?>]' value='<?php echo $issue->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $issue->id);?>
          </td>
          <td><?php echo zget($lang->issue->severityList, $issue->severity);?></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $issue->pri;?>' title='<?php echo zget($lang->issue->priList, $issue->pri, $issue->pri);?>'><?php echo $issue->pri == '0' ? '' : zget($lang->issue->priList, $issue->pri, $issue->pri);?></span></td>
          <td class='text-left nobr c-name' title="<?php echo $issue->title?>"><?php if(!common::printLink('issue', 'view', "issueID=$issue->id", $issue->title, '', "class='iframe'", true, true)) echo $issue->title;?></td>
          <td><?php echo zget($lang->issue->typeList, $issue->type);?></td>
          <td class="c-name" title="<?php echo $allProject[$issue->project];?>"><?php echo $allProject[$issue->project];?></td>
        </tr>
        <?php endforeach;?>
        <?php echo html::hidden('lib', $libID);?>
      </tbody>
    </table>
    <?php if($issues):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->assetlib->import, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::linkButton($lang->goback, $this->session->issueList);?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
