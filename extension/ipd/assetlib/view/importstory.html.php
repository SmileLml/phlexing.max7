<?php
/**
 * The importstory view file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     assetlib
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('libID', $libID);?>
<?php js::set('projectID', $projectID);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->storyList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="table-row w-600px">
      <div class="table-col w-300px">
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->assetlib->selectProject;?></span>
          <?php echo html::select('fromProject', $allProject, $projectID, "onchange='reload(this.value)' class='form-control chosen'");?>
        </div>
      </div>
      <div class="table-col w-300px">
        <?php if($project->hasProduct):?>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->assetlib->selectProduct;?></span>
          <?php echo html::select('fromProduct', $products, $productID, "class='form-control chosen'");?>
        </div>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=assetStory');?>" data-module='assetStory' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importStory' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "libID=$libID&projectID=$projectID&productID=$productID&orderBy=%s&browseType=$browseType&queryID=$queryID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-pri'>  <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
          <th>                <?php common::printOrderLink('title', $orderBy, $vars, $lang->assetlib->name);?></th>
          <th class='w-150px'><?php common::printOrderLink('category', $orderBy, $vars, $lang->story->category);?></th>
          <th class='w-80px'> <?php common::printOrderLink('estimate', $orderBy, $vars, $lang->assetlib->estimate);?></th>
          <th class='w-200px'><?php common::printOrderLink('plan', $orderBy, $vars, $lang->assetlib->plan);?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $story):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='storyIdList[<?php echo $story->id?>]' value='<?php echo $story->id;?>' />
              <label></label>
            </div>
            <?php printf('%03d', $story->id);?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $story->pri;?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo $story->pri == '0' ? '' : zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
          <td class='text-left nobr c-name' title="<?php echo $story->title;?>"><?php if(!common::printLink('projectstory', 'view', "storyID=$story->id&projectID=$projectID", $story->title, '', "class='iframe'", true, true)) echo $story->title;?></td>
          <td><?php echo zget($lang->story->categoryList, $story->category);?></td>
          <td><?php echo $story->estimate;?></td>
          <td class="c-name"><?php echo isset($plans[$story->plan]) ? $plans[$story->plan] : '';?></td>
        </tr>
        <?php endforeach;?>
        <?php echo html::hidden('lib', $libID);?>
      </tbody>
    </table>
    <?php if($stories):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->assetlib->import, '', 'btn btn-secondary');?>
      </div>
      <div class="btn-toolbar">
        <?php echo html::linkButton($lang->goback, $this->session->storyList);?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
