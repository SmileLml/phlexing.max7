<?php
/**
 * The batch edit view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('dittoNotice', $this->lang->story->dittoNotice);?>
<?php js::set('storyType', $storyType);?>
<?php js::set('app', $this->app->tab);?>
<?php if(isset($resetActive)) js::set('resetActive', true);?>
<?php js::set('showFields', $showFields);?>
<div class='main-content' id='mainContent'>
<div class='main-header'>
  <h2>
    <?php echo $lang->URCommon . $lang->hyphen . $lang->story->batchEdit;?>
  </h2>
  <div class='pull-right btn-toolbar'>
    <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchEditFields')?>
    <?php include $this->app->getModuleRoot() . 'common/view/customfield.html.php';?>
  </div>
</div>
<?php if(isset($suhosinInfo)):?>
<div id='suhosinInfo' class='alert alert-info'><?php echo $suhosinInfo;?></div>
<?php else:?>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($storyType == 'requeirment' and $field == 'stage') continue;
    if($field) $visibleFields[$field] = '';
}
?>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID&executionID=$executionID&branch=$branch&storyType=$storyType")?>" id="batchEditForm">
  <div class="table-responsive">
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='c-id'> <?php echo $lang->idAB;?></th>
          <?php if($branchProduct):?>
          <th class='c-branch'><?php echo $lang->story->branch;?></th>
          <?php endif;?>
          <th class='c-module'><?php echo $lang->story->module;?></th>
          <th class='c-roadmap'><?php echo $lang->roadmap->common;?></th>
          <th class='c-title required'><?php echo $lang->story->title;?></th>
          <th class='c-estimate'> <?php echo $lang->story->estimateAB;?></th>
          <th class='c-category'><?php echo $lang->story->category;?></th>
          <th class='c-pri' title=<?php echo $lang->story->pri;?>> <?php echo $lang->priAB;?></th>
          <th class='c-user'> <?php echo $lang->story->assignedTo;?></th>
          <th class='c-duration<?php echo zget($visibleFields, 'duration', ' hidden')?>'> <?php echo $lang->story->duration;?></th>
          <th class='c-bsa<?php echo zget($visibleFields, 'BSA', ' hidden')?>'> <?php echo $lang->story->BSA . " <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content='{$lang->demand->bsaTip}'></icon>";?></th>
          <th class='c-source<?php echo zget($visibleFields, 'source', ' hidden')?>'> <?php echo $lang->story->source;?></th>
          <th class='c-status'><?php echo $lang->story->status;?></th>
          <th class='c-keywords<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->story->keywords;?></th>
          <th class='c-mailto<?php echo zget($visibleFields, 'mailto', ' hidden')?>'><?php echo $lang->story->mailto;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $storyID => $story):?>
        <tr>
          <td><?php echo $storyID . html::hidden("storyIdList[$storyID]", $storyID);?></td>
          <?php if($branchProduct):?>
          <td class='text-left'>
            <?php $disabled = $products[$story->product]->type == 'normal' ? "disabled='disabled'" : '';?>
            <?php if($products[$story->product]->type == 'normal') $branchTagOption[$story->product] = array();?>
            <?php echo html::select("branches[$storyID]", $branchTagOption[$story->product], $story->branch, "class='form-control picker-select' data-drop-width='auto' onchange='loadBranches($story->product, this.value, $storyID);' $disabled");?>
          </td>
          <?php endif;?>
          <td class='text-left<?php echo zget($visibleFields, 'module')?>'>
            <?php echo html::select("modules[$storyID]", zget($moduleList, $story->id, array(0 => '/')), $story->module, "class='form-control picker-select' data-drop-width='auto'");?>
          </td>
          <td class='text-left'>
            <?php
            $roadmapCondition = (isset($allRoadmaps[$story->roadmap]) && in_array($allRoadmaps[$story->roadmap]->status, array('launching', 'launched', 'closed')));
            $storyCondition   = in_array($story->status, array('draft', 'reviewing', 'closed'));
            if($roadmapCondition or $storyCondition)
            {
                $name = isset($allRoadmaps[$story->roadmap]) ? $allRoadmaps[$story->roadmap]->name : '';
                echo html::input("roadmaps[$storyID]", $name, "class='form-control' disabled");
                echo html::hidden("roadmaps[$storyID]", $story->roadmap);
            }
            else
            {
                $storyRoadmap = isset($allRoadmaps[$story->roadmap]) ? array($story->roadmap => $allRoadmaps[$story->roadmap]->name) : array();
                echo html::select("roadmaps[$storyID]", $roadmaps + $storyRoadmap, $story->roadmap, "class='form-control picker-select' data-drop-width='auto'");
            }
            ?>
          </td>
          <td title='<?php echo $story->title?>'>
            <div class="input-group">
              <div class="input-control has-icon-right story-input">
                <?php echo html::input("", $story->title, "class='form-control input-story-title' disabled"); ?>
                <?php echo html::hidden("titles[$storyID]", $story->title); ?>

                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("colors[$storyID]", $story->color, "class='colorpicker' data-wrapper='input-control-icon-right' data-icon='color' data-btn-tip='{$lang->story->colorTag}' data-update-color='#titles\\[{$storyID}\\]'");?>
                </div>
              </div>
            </div>
          </td>

          <td><?php echo html::input("estimates[$storyID]", $story->estimate, "class='form-control'"); ?></td>
          <?php if(empty($lang->story->categoryList[$story->category]) && !empty($lang->story->ipdCategoryList[$story->category])) $lang->story->categoryList[$story->category] = $lang->story->ipdCategoryList[$story->category];?>
          <td><?php echo html::select("category[$storyID]", $lang->story->categoryList, $story->category, 'class="form-control picker-select" data-drop-width="auto"');?></td>
          <?php if(!empty($lang->story->ipdCategoryList[$story->category])) unset($lang->story->categoryList[$story->category]);?>
          <td><?php echo html::select("pris[$storyID]", $priList, $story->pri, 'class=form-control');?></td>
          <td class='text-left'><?php echo html::select("assignedTo[$storyID]", $users, $story->assignedTo, "class='form-control picker-select' data-drop-width='auto'");?></td>
          <td <?php echo zget($visibleFields, 'duration', "class='hidden'")?>><?php echo html::select("durations[$storyID]", $lang->demand->durationList, $story->duration, "class='form-control picker-select' data-drop-width='auto' id='duration_$storyID'");?></td>
          <td <?php echo zget($visibleFields, 'BSA', "class='hidden'")?>><?php echo html::select("BSAs[$storyID]", $lang->demand->bsaList, $story->BSA, "class='form-control picker-select' data-drop-width='auto' id='BSA_$storyID'");?></td>
          <td <?php echo zget($visibleFields, 'source', "class='hidden'")?>><?php echo html::select("sources[$storyID]",  $sourceList, $story->source, "class='form-control picker-select' data-drop-width='auto' id='source_$storyID'");?></td>
          <td class='story-<?php echo $story->status;?>'><?php echo $this->processStatus('story', $story);?></td>
          <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$storyID]", $story->keywords, 'class="form-control"');?></td>
          <td <?php echo zget($visibleFields, 'mailto', "class='hidden'")?>><?php echo html::select("mailtos[$storyID][]", $mailto, $story->mailto, 'class="form-control picker-select" multiple');?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='<?php echo count($visibleFields) + ($branchProduct ? 8 : 7);?>' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo ($this->app->tab == 'product' or $this->app->tab == 'execution') ? html::a($this->session->storyList, $lang->goback, '', "class='btn btn-back btn-wide'") : html::backButton();?>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</form>
<?php endif;?>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
