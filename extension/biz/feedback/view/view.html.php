<?php
/**
 * The view file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     feedback
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('sysurl', common::getSysUrl());?>
<style>
#mainContent .main-col .detail-content {word-break: break-all;}
</style>
<?php js::set('browseType', $browseType);?>
<?php js::set('moduleID', $feedback->module);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $jumpMethod = empty($this->app->user->feedback) ? 'admin' : 'browse';?>
    <?php $browseLink = $app->session->feedbackList != false ? $app->session->feedbackList : inlink($jumpMethod, 'browseType=unclosed');?>
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, "<i class='icon-back'></i> " . $lang->goback, '', "class='btn btn-primary'");?>
    <div class='divider'></div>
    <?php endif;?>
    <div class='page-title'>
      <span class='label label-id'><?php echo $feedback->id;?></span>
      <strong>
      <?php
      if($feedback->public) echo "<span class='label label-info'>{$lang->feedback->public}</span>";
      echo $feedback->title;
      ?>
      </strong>
      <?php $status = $this->processStatus('feedback', $feedback);?>
      <?php echo " <span class='label label-status status-{$feedback->status}'>{$status}</span>";?>
      <?php echo " <span class='label label-info'>{$product->name}</span>";?>
      <?php if($feedback->deleted) echo "<span class='label label-danger'>{$lang->feedback->deleted}</span>";?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col col-8'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->feedback->desc;?></div>
        <div class='detail-content'><?php echo $feedback->desc;?></div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $feedback->files, 'fieldset' => 'true', 'object' => $feedback, 'method' => 'view', 'showDelete' => false));?>
      <?php if($config->vision != 'lite' && !empty($relations)):?>
      <?php foreach($relations as $type => $relationList):?>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->feedback->$type;?></div>
        <?php $module = $type == 'userStory' ? 'requirement' : $type;?>
        <div class='detail-content'>
          <table class='table table-bordered table-fixed'>
            <thead>
              <tr class='text-center'>
                <th class='w-80px'><?php echo $lang->feedback->id;?></th>
                <?php if($this->config->vision == 'or' and $type == 'userStory'):?>
                <th class='w-120px'><?php echo $lang->story->product;?></th>
                <?php endif;?>
                <th class='w-60px'>P</th>
                <th><?php echo $lang->feedback->$type;?></th>
                <th class='w-120px'><?php echo $lang->story->stage;?>
                </th>
                <th class='w-120px'><?php echo $lang->story->status;?></th>
                <?php if($this->config->vision == 'or' and $type == 'userStory'):?>
                <th class='w-120px'><?php echo $lang->story->roadmap;?></th>
                <?php endif;?>
              </tr>
            </thead>
            <tbody>
              <?php foreach($relationList as $relation):?>
              <tr class='text-center'>
                <td><?php echo $relation->id;?></td>
                <?php if($this->config->vision == 'or' and $type == 'userStory'):?>
                <td title='<?php echo zget($products, $relation->product);?>'><?php echo zget($products, $relation->product);?></td>
                <?php endif;?>
                <td>
                  <span class='<?php echo 'label-pri label-pri-' . zget($lang->$module->priList, $relation->pri);?>'><?php echo zget($lang->$module->priList, $relation->pri);?></span>
                </td>
                <td class='text-left c-title' title='<?php echo $relation->title;?>'>
                  <?php echo common::hasPriv($module, 'view') ? html::a($this->createLink($module, 'view', "id={$relation->id}", 'html'), $relation->title, '', "class='text-primary' data-width='95%'") : $relation->title;?>
                </td>
                <td><?php echo zget($lang->story->stageList, $relation->stage);?></td>
                <td><?php echo $relation->statusLabel;?></td>
                <?php if($this->config->vision == 'or' && $type == 'userStory'):?>
                <td title='<?php echo zget($roadmaps, $relation->roadmap);?>'><?php echo zget($roadmaps, $relation->roadmap);?></td>
                <?php endif;?>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endforeach;?>
      <?php endif;?>
    </div>
    <?php $this->printExtendFields($feedback, 'div', "position=left&inCell=1&inForm=0");?>
    <div class='cell'><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php $feedback->browseType = $browseType;?>
        <?php $feedback->userList   = $users;?>
        <?php echo $this->feedback->buildOperateMenu($feedback, 'view');?>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->feedback->lblBasic;?></div>
        <table class='table table-data table-condensed table-borderless'>
          <tr>
            <th class='w-80px'><?php echo $lang->feedback->product?></th>
            <td><?php echo (common::hasPriv('product', 'view') and in_array($feedback->product, explode(',', $this->app->user->view->products)) and isset($products[$feedback->product]) and !$product->shadow) ? html::a($this->createLink('product', 'view', "id={$feedback->product}"), zget($products, $feedback->product)) : $product->name;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->module;?></th>
            <?php
            $moduleTitle = '';
            ob_start();
            if(empty($modulePath))
            {
                $moduleTitle .= '/';
                echo "/";
            }
            else
            {
               foreach($modulePath as $key => $module)
               {
                   $moduleTitle .= $module->name;
                   echo $module->name;
                   if(isset($modulePath[$key + 1]))
                   {
                       $moduleTitle .= '/';
                       echo $lang->arrow;
                   }
               }
            }
            $printModule = ob_get_contents();
            ob_end_clean();
            ?>
            <td title='<?php echo $moduleTitle?>'><?php echo $printModule?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->status?></th>
            <td class='status-<?php echo $feedback->status;?>'><?php echo $status;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->type;?></th>
            <td><?php echo zget($lang->feedback->typeList, $feedback->type, '');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->pri;?></th>
            <td><span class='label-pri <?php echo 'label-pri-' . $feedback->pri;?>' title='<?php echo zget($lang->feedback->priList, $feedback->pri);?>'><?php echo $feedback->pri == '0' ? $lang->noData : zget($lang->feedback->priList, $feedback->pri)?></span></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->solution;?></th>
            <td><?php echo zget($lang->feedback->solutionList, $feedback->solution, '');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->openedBy?></th>
            <td>
            <?php
            echo zget($users, $feedback->openedBy);
            echo $lang->at . $feedback->openedDate;
            ?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->assignedTo?></th>
            <td><?php echo zget($users, $feedback->assignedTo);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->feedbackBy;?></th>
            <td><?php echo $feedback->feedbackBy;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->notifyEmail;?></th>
            <td><?php echo $feedback->notifyEmail;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->mailto;?></th>
            <td><?php $mailto = explode(',', str_replace(' ', '', $feedback->mailto)); foreach($mailto as $account) echo ' ' . $users[$account]; ?></td>
          </tr>
          <tr>
            <th><?php echo $lang->feedback->keywords;?></th>
            <td><?php echo $feedback->keywords;?></td>
          </tr>
          <?php if($feedback->activatedBy):?>
          <tr>
           <th><?php echo $lang->feedback->activatedBy;?></th>
           <td><?php echo zget($users, $feedback->activatedBy) . $lang->at . $feedback->activatedDate;?></td>
          </tr>
          <?php endif;?>
          <?php if($feedback->reviewedBy):?>
          <tr>
            <th><?php echo $lang->feedback->reviewedBy;?></th>
            <td>
              <?php
              foreach(explode(',', $feedback->reviewedBy) as $reviewedBy) echo zget($users, $reviewedBy) . ' ';
              echo $lang->at . substr($feedback->reviewedDate, 0, 10);
              ?>
            </td>
          </tr>
          <?php endif;?>
          <?php if($feedback->processedBy):?>
          <tr>
            <th><?php echo $lang->feedback->processedBy?></th>
            <td><?php echo zget($users, $feedback->processedBy) . $lang->at . $feedback->processedDate;?></td>
          </tr>
          <?php endif;?>
          <?php if($feedback->closedBy):?>
          <tr>
            <th><?php echo $lang->feedback->closedBy?></th>
            <td><?php echo zget($users, $feedback->closedBy) . $lang->at . $feedback->closedDate;?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->feedback->closedReason?></th>
            <td><?php echo zget($lang->feedback->closedReasonList, $feedback->closedReason);?></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#source' data-toggle='tab'><?php echo $lang->feedback->sourceInfo;?></a></li>
          <li><a href='#internalContact' data-toggle='tab'><?php echo $lang->feedback->internalContact;?></a></li>
        </ul>
        <div class='tab-content'>
          <div class='tab-pane active' id='source'>
            <table class="table table-data">
              <tbody>
                <tr class="has-top-line">
                  <th class='thWidth'><?php echo $lang->feedback->feedbackBy;?></th>
                  <td><?php echo $feedback->feedbackBy;?></td>
                </tr>
                <tr>
                  <th class='thWidth'><?php echo $lang->feedback->company;?></th>
                  <td><?php echo $feedback->source;?></td>
                </tr>
                <tr>
                  <th class='thWidth'><?php echo $lang->feedback->email;?></th>
                  <td><?php echo $feedback->notifyEmail;?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class='tab-pane' id='internalContact'>
            <table class='table table-data table-condensed'>
              <?php foreach($contacts as $contact):?>
              <tr>
                <th class='w-80px text-top'><?php echo $contact->realname ? $contact->realname : $contact->account;?></th>
                <td>
                  <?php
                  if($contact->mobile)  echo "<p>" . $lang->user->mobile   . ': '. $contact->mobile   . "</p>";
                  if($contact->email)   echo "<p>" . $lang->user->email    . ': '. $contact->email    . "</p>";
                  if($contact->phone)   echo "<p>" . $lang->user->phone    . ': '. $contact->phone    . "</p>";
                  if($contact->qq)      echo "<p>" . $lang->user->qq       . ': '. $contact->qq       . "</p>";
                  if($contact->skype)   echo "<p>" . $lang->user->skype    . ': '. $contact->skype    . "</p>";
                  ?>
                </td>
              </tr>
              <?php endforeach;?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <?php $this->printExtendFields($feedback, 'div', "position=right&inCell=1&inForm=0");?>
  </div>
</div>
<div id='mainActions' class='main-actions'>
  <?php common::printPreAndNext($preAndNext);?>
</div>
<?php include './selectproject.html.php';?>
<script>
$(function()
{
    $(".effort").modalTrigger({width:1024, iframe:true, transition:'elastic'});

    $('#feedbackTree').tree(
    {
        name: 'feedbackTree',
        initialState: 'preserve'
    });
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
