<?php
/**
 * The change view file of demand module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     demand
 * @version     $Id: change.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('lastReviewer', explode(',', $lastReviewer))?>
<?php js::set('page', 'change')?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $demand->id;?></span>
        <?php echo html::a($this->createLink('demand', 'view', "demandID=$demand->id"), $demand->title, '', 'class="demand-title"');?>
        <small><?php echo $lang->arrow . ' ' . $lang->demand->change;?></small>
      </h2>
    </div>
    <form class="main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->demand->reviewedBy;?></th>
          <td id='reviewerBox'>
            <div class="input-group">
              <?php echo html::select('reviewer[]', $reviewers, $reviewer, "class='form-control picker-select' multiple" . ($this->demand->checkForceReview() ? ' required' : ''));?>
              <?php if(!$this->demand->checkForceReview()):?>
              <span class="input-group-addon">
              <?php echo html::checkbox('needNotReview', $lang->demand->needNotReview, '', "id='needNotReview' {$needReview}");?>
              </span>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->demand->status;?></th>
          <td><?php echo html::hidden('status', $demand->status);?></td>
        </tr>
        <?php $this->printExtendFields($demand, 'table');?>
        <tr>
          <th><?php echo $lang->demand->title;?></th>
          <td>
            <div class='input-group'>
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $demand->title, 'class="form-control demand-title"');?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" title="<?php echo $lang->task->colorTag ?>"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->demand->colorTag; ?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $demand->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color=".demand-title"  data-provide="colorpicker">
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->spec;?></th>
          <td><?php echo html::textarea('spec', htmlSpecialString($demand->spec), 'rows=8 class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->verify;?></th>
          <td><?php echo html::textarea('verify', htmlSpecialString($demand->verify), 'rows=6 class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->demand->comment;?></th>
          <td><?php echo html::textarea('comment', '', 'rows=5 class="form-control"');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->attatch;?></th>
          <td>
          <?php echo $this->fetch('file', 'printFiles', array('files' => $demand->files, 'fieldset' => 'false', 'object' => $demand, 'method' => 'edit'));?>
          <?php echo $this->fetch('file', 'buildoldform');?>
          </td>
        </tr>
        <tr>
          <td></td>
          <td class='text-center form-actions'>
            <?php
            echo html::hidden('lastEditedDate', $demand->lastEditedDate);
            echo html::commonButton($lang->save, "id='saveButton'", 'btn btn-primary btn-wide');
            echo html::commonButton($lang->story->doNotSubmit, "id='saveDraftButton'", 'btn btn-secondary btn-wide');
            if(!isonlybody()) echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
    <hr class='small' />
    <div class='main'>
      <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
