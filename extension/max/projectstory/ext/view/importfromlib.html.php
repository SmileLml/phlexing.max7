<?php
/**
 * The importfromlib view file of projectstory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     projectstory
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
js::set('projectID', $projectID);
js::set('productID', $productID);
js::set('storyType', $storyType);
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a($this->session->storyList, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class='input-group w-300px'>
      <span class='input-group-addon'><?php echo $lang->assetlib->selectLib;?></span>
      <?php echo html::select('fromlib', $libraries, $libID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='queryBox' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=projectstory');?>" data-module='projectstory' class='show cell'></div>
<div id='mainContent'>
  <form class='main-table' method='post' target='hiddenwin' id='importFromLib' data-ride='table'>
    <table class='table has-sort-head table-fixed'>
      <thead>
        <?php $vars = "projectID=$projectID&productID=$productID&libID=$libID&orderBy=%s&browseType=$browseType&queryID=$queryID&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <?php if($product->type != 'normal'):?>
          <th class='c-branch text-left'><?php echo sprintf($lang->product->branch, $lang->product->branchName[$product->type]);?></th>
          <?php endif;?>
          <th class='text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->story->title);?></th>
          <th class='w-100px'><?php common::printOrderLink('pri', $orderBy, $vars, $lang->story->pri);?></th>
          <th class='w-100px'><?php common::printOrderLink('estimate', $orderBy, $vars, $lang->story->estimate);?></th>
        </tr>
      </thead>
      <tbody>
      <?php $i = 0;?>
      <?php foreach($stories as $story):?>
      <tr>
        <td class='c-id'>
          <div class="checkbox-primary">
            <input type='checkbox' name='storyIdList[<?php echo $story->id?>]' value='<?php echo $story->id;?>' />
            <label></label>
          </div>
          <?php printf('%03d', $story->id);?>
        </td>
        <?php if($product->type != 'normal'):?>
        <td id='branchBox'><?php echo html::select("branches[$story->id]", $branches, '', "class='form-control chosen'");?></td>
        <?php endif;?>
        <td class='c-name' title="<?php echo $story->title;?>"><?php echo html::a($this->createLink('assetlib', 'storyView', "storyID=$story->id", '', true), $story->title, '', "class='iframe' data-width='70%'");?></td>
        <td>
          <span class="label-pri label-pri-<?php echo $story->pri;?>" title="<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>"><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span>
        </td>
        <td><?php echo $story->estimate . $this->config->hourUnit;?></td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($stories):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll;?></label></div>
      <div class='table-actions btn-toolbar show-always'>
        <?php echo html::submitButton($lang->projectstory->import, '', 'btn btn-secondary');?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
