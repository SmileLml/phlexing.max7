<?php
/**
 * The importdoc view file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('libID', $libID);?>
<?php js::set('projectID', $projectID);?>
<?php js::set('docLibID', $docLibID);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $backLink = $objectType == 'practice' ? $this->session->practiceList : $this->session->componentList;?>
    <?php echo html::a($backLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="table-row w-600px">
      <div class="table-col w-300px">
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->assetlib->selectProject;?></span>
          <?php echo html::select('fromProject', $allProject, $projectID, "onchange='reload(this.value)' class='form-control chosen'");?>
        </div>
      </div>
      <div class="table-col w-300px">
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->assetlib->selectDocLib;?></span>
          <?php echo html::select('fromDocLib', $docLibs, $docLibID, "class='form-control chosen'");?>
        </div>
      </div>
    </div>
  </div>
</div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importPractice' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "libID=$libID&projectID=$projectID&docLibID=$docLibID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th><?php common::printOrderLink('title', $orderBy, $vars, $lang->assetlib->name);?></th>
          <th class='w-400px'><?php common::printOrderLink('project', $orderBy, $vars, $lang->assetlib->project);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($docs as $doc):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='docIdList[<?php echo $doc->id?>]' value='<?php echo $doc->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $doc->id);?>
          </td>
          <td class='text-left nobr c-name' title="<?php echo $doc->title;?>"><?php if(!common::printLink('doc', 'view', "docID=$doc->id&version=0", $doc->title, '', "data-app='doc'")) echo $doc->title;?></td>
          <td class="c-name" title="<?php echo $allProject[$doc->project];?>"><?php echo $allProject[$doc->project];?></td>
        </tr>
        <?php endforeach;?>
        <?php echo html::hidden('lib', $libID);?>
      </tbody>
    </table>
    <?php if($docs):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->assetlib->import, '', 'btn btn-secondary');?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
