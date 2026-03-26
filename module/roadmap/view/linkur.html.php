<?php
/**
 * The link story view of roadmap module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     roadmap
 * @version     $Id: linkstory.html.php 5096 2013-07-11 07:02:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<div id='queryBox' data-module='story' class='show no-margin' data-url="<?php echo $this->createLink('search', 'buildOldForm', 'module=story');?>"></div>
<div id='unlinkStoryList'>
  <form class="main-table table-story" data-ride="table" method="post" target='hiddenwin' id='unlinkedStoriesForm' action="<?php echo $this->createLink('roadmap', 'linkUR', "roadmapID=$roadmap->id&browseType=$browseType&param=$param&orderBy=$orderBy")?>">
    <div class='table-header hl-primary text-primary strong'>
      <?php echo html::icon('unlink');?> <?php echo $lang->roadmap->unlinkedUR;?>
    </div>
    <table class='table tablesorter'>
      <thead>
        <tr>
          <th class='c-id text-left'>
            <?php if($allStories):?>
            <div class="checkbox-primary check-all tablesorter-noSort" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='c-pri' title='<?php echo $lang->pri;?>'><?php echo $lang->priAB;?></th>
          <th class='c-roadmap'><?php echo $lang->story->roadmap;?></th>
          <th class='text-left'><?php echo $lang->requirement->title;?></th>
          <th class='c-module'><?php echo $lang->story->module;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $unlinkedCount = 0;?>
        <?php foreach($allStories as $story):?>
        <tr>
          <td class='c-id text-left'>
            <?php echo html::checkbox('stories', array($story->id => sprintf('%03d', $story->id)));?>
          </td>
          <td><span class='<?php echo $story->pri ?  "label-pri label-pri-{$story->pri}" : "";?>' title='<?php echo zget($lang->story->priList, $story->pri)?>'><?php echo zget($lang->story->priList, $story->pri)?></span></td>
          <td class='c-roadmap' title="<?php echo $story->roadmapTitle;?>"><?php echo $story->roadmapTitle;?></td>
          <td class='text-left nobr' title='<?php echo $story->title?>'>
            <?php
            if($story->parent > 0) echo "<span class='label label-badge label-light' title={$gradeGroup[$story->type][$story->grade]}>{$gradeGroup[$story->type][$story->grade]}</span>";
            echo html::a($this->createLink('story', 'view', "storyID=$story->id&version=0&param=0&storyType=requirement", '', true), $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");
            ?>
          </td>
          <td title='<?php echo $modules[$story->module]?>' class='text-left c-name'><?php echo $modules[$story->module];?></td>
          <td><?php echo zget($users, $story->openedBy);?></td>
          <td>
            <span class='status-story status-<?php echo $story->status?>'>
              <?php echo $this->processStatus('story', $story);?>
            </span>
          </td>
        </tr>
        <?php $unlinkedCount++;?>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php if($unlinkedCount):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar">
        <?php echo html::submitButton($lang->roadmap->linkUR, '', 'btn');?>
      </div>
      <?php endif;?>
      <div class="btn-toolbar">
        <?php echo html::a(inlink('view', "roadmapID=$roadmap->id&type=story&orderBy=$orderBy"), $lang->goback, '', "class='btn'");?>
      </div>
      <div class='table-statistic'></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>
<script>
$(function()
{
    $('#unlinkStoryList .tablesorter').sortTable();
    setForm();
});
</script>
