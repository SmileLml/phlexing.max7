<?php
/**
 * The ajax select story view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content">
  <div class='main-header'>
    <h2><?php echo $lang->testcase->selectStory;?></h2>
  </div>
  <form class='main-table' data-ride='table' method='post' target='hiddenwin' id='storyForm' action='<?php echo $this->createLink('testcase', 'exportTemplate', "productID={$product->id}")?>'>
    <table class='table table-fixed table-custom' id='storyList'>
      <thead>
        <tr>
          <th class='c-id'>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-pri'><?php echo $lang->priAB;?></th>
          <th class='w-80px'><?php echo $lang->story->module;?></th>
          <th class='w-80px'><?php echo $lang->story->title;?></th>
          <th class='w-80px'><?php echo $lang->story->stage;?></th>
          <th class='w-90px'><?php echo $lang->openedByAB;?></th>
          <th class='w-80px'><?php echo $lang->story->estimateAB;?></th>
        </tr>
      </thead>
      <?php if($stories):?>
      <tbody>
        <?php foreach($stories as $story):?>
        <?php $storyLink = $this->createLink('story', 'view', "storyID=$story->id");?>
        <?php if(empty($story->children)):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='stories[]'  value='<?php echo $story->id;?>' <?php if(strpos(",{$selectedStories},", "{$story->id}") !== false) echo 'checked';?>/>
              <label></label>
            </div>
            <?php echo html::hidden("products[$story->id]", $story->product);?>
            <?php printf('%03d', $story->id);?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri);?></span></td>
          <td class='text-left' title='<?php echo zget($modules, $story->module, '')?>'><?php echo zget($modules, $story->module, '')?></td>
          <td class='text-left nobr' title="<?php echo $story->title?>"><?php echo html::a($storyLink, $story->title);?></td>
          <td><?php echo zget($lang->story->stageList, $story->stage);?></td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td><?php echo $story->estimate;?></td>
        </tr>
        <?php else:?>
        <?php foreach($story->children as $child):?>
        <tr>
          <td class='c-id'>
            <div class="checkbox-primary">
              <input type='checkbox' name='stories[]'  value='<?php echo $child->id;?>' <?php if(strpos(",{$selectedStories},", "{$child->id}") !== false) echo 'checked';?>/>
              <label></label>
            </div>
            <?php echo html::hidden("products[$child->id]", $child->product);?>
            <?php printf('%03d', $child->id);?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->story->priList, $child->pri)?>'><?php echo zget($lang->story->priList, $child->pri);?></span></td>
          <td class='text-left' title='<?php echo zget($modules, $child->module, '')?>'><?php echo zget($modules, $child->module, '')?></td>
          <td class='text-left nobr' title="<?php echo $child->title?>"><span class='label'><?php echo $lang->story->childrenAB;?></span> <?php echo html::a($storyLink, $story->title . ' / ' . $child->title);?></td>
          <td><?php echo zget($lang->story->stageList, $child->stage);?></td>
          <td><?php echo zget($users, $child->openedBy);?></td>
          <td><?php echo $child->estimate;?></td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
    </table>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="btn-toolbar">
        <div class='input-group w-250px'>
          <?php
          echo html::input('num', '10', "class='form-control w-60px' autocomplete='off' style='border-right:0px;'");
          echo "<span class='input-group-addon'></span>";
          echo html::select('fileType', array('xlsx' => 'xlsx', 'xls' => 'xls'), 'xlsx', "class='form-control w-80px'");
          echo "<span class='input-group-btn'>" . html::submitButton($lang->testcase->exportTemplate) . "</span>";
          ?>
        </div>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>
<script>
$(function()
{
    $.toggleQueryBox(true);

    $(document).on('change', 'input:checked', function()
    {
        selectedStories = '';
        $('.cell-id :checked').each(function()
        {
            if($(this).prop('checked') && $(this).val()) selectedStories += $(this).val() + ','
        });
        $.cookie('selectedStories', false);
        $.cookie('selectedStories[<?php echo $product->id?>]', selectedStories);
    });

    $('#submit').click(function()
    {
        setTimeout(function()
        {
            $('#submit').removeAttr('disabled')
        }, 1000);
    });
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
