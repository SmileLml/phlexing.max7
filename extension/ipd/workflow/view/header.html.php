<?php
/**
 * The common header view file of workfloweditor module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     商业软件，非开源软件
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     workfloweditor
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php if($editorMode == 'quick' && $flow->buildin) die(js::error($this->lang->workflow->tips->buildinFlow) . js::locate('back'));?>

<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<style><?php helper::import($this->app->getExtensionRoot() . 'biz/workflow/css/header.css');?></style>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('editorMode', $editorMode); ?>
<?php js::set('unknownError', $lang->workfloweditor->error->unknown); ?>
<?php $currentModule = $this->app->getModuleName(); ?>
<?php $currentMethod = $this->app->getMethodName(); ?>
<div id='editorNav'>
  <div id='editorNavLeft'>
    <?php $backLink = $this->session->workflowList ? $this->session->workflowList : $this->createLink('workflow', 'browseFlow');?>
    <?php echo baseHTML::a($backLink, "<i class='icon-angle-left'></i>" , 'class="btn btn-link"');?>
    <?php
    $workflowGroupID = (int)$this->session->workflowGroupID;
    $workflowGroup   = $this->loadModel('workflowgroup')->getById($workflowGroupID);
    ?>
    <strong><?php echo $flow->name . ($workflowGroup ? " ({$lang->workflowgroup->template} : {$workflowGroup->name}) " : '');?></strong>
  </div>
  <div id='editorNavCenter'>
    <strong class='title'><?php echo $editorMode == 'quick' ? $lang->workfloweditor->quickEditor : $lang->workfloweditor->advanceEditor; ?></strong>
  </div>
  <div id='editorNavRight'>
    <?php
    if($editorMode == 'quick')
    {
        echo baseHTML::a('#switchConfirmModal', sprintf($lang->workfloweditor->switchTo, $lang->workfloweditor->advanceEditor), "class='btn btn-primary' data-toggle='modal'");
    }
    elseif(!$flow->buildin)
    {
        extCommonModel::printLink('workflow', 'ui', "module={$flow->module}&action=create&groupID={$workflowGroupID}", sprintf($lang->workfloweditor->switchTo, $lang->workfloweditor->quickEditor), "class='btn btn-primary'");
    }
    ?>
  </div>
</div>
<?php if($editorMode == 'quick'): ?>
<div class='modal fade' id='switchConfirmModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
      </div>
      <div class='modal-body text-center'>
        <p><?php echo $lang->workfloweditor->switchConfirmMessage; ?></p>
        <div>
          <?php extCommonModel::printLink('workflowfield', 'browse', "module={$flow->module}&orderBy=order&groupID={$workflowGroupID}", $lang->workfloweditor->confirmSwitch, "class='btn btn-primary not-in-app'");?>
          <button type='button' class='btn' data-dismiss='modal'><?php echo $lang->workfloweditor->cancelSwitch; ?></button>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<div id='editorMenu'>
  <nav id='editorSteps' class='editor-steps-<?php echo $editorMode; ?>'>
    <ul class='nav nav-primary'>
      <?php
      $stepList = $editorMode == 'quick' ? $lang->workfloweditor->quickSteps : $lang->workfloweditor->advanceSteps;
      if($flow->buildin)
      {
          unset($stepList['subTable']);
          unset($stepList['label']);
          unset($lang->workfloweditor->moreSettings['setReport']);
          unset($lang->workfloweditor->moreSettings['fulltext']);

          if(in_array($flow->module, $this->config->workflow->buildin->noApproval)) unset($lang->workfloweditor->moreSettings['approval']);
      }

      if($workflowGroupID > 0)
      {
          unset($lang->workfloweditor->moreSettings['relation']);
          unset($lang->workfloweditor->moreSettings['setValue']);
          unset($lang->workfloweditor->moreSettings['setExport']);
          unset($lang->workfloweditor->moreSettings['setSearch']);
      }

      $stepUrls    = array();
      $currentStep = -1;
      $index       = 0;
      foreach($stepList as $stepItem)
      {
          $subMenu = array();
          if(isset($stepItem['subMenu'])) $subMenu = $stepItem['subMenu'];
          if(isset($stepItem['link']))    $stepItem = $stepItem['link'];

          list($label, $moduleName, $methodName) = explode('|', $stepItem);
          if($methodName == 'more')
          {
              foreach($lang->workfloweditor->moreSettings as $moreSetting)
              {
                  $moreSetting = explode('|', $moreSetting);
                  if($flow->module != 'charter' && $moreSetting[1] == 'workflow' && $moreSetting[2] == 'setapproval' && $config->edition == 'biz') continue;
                  if(commonModel::hasPriv($moreSetting[1], $moreSetting[2]))
                  {
                      $moduleName = $moreSetting[1];
                      $methodName = $moreSetting[2];
                      $stepParams = $moduleName == 'workflow' && $methodName != 'setapproval' ? sprintf($moreSetting[3], $flow->id) : sprintf($moreSetting[3], $flow->module);
                      break;
                  }
              }

              /* Hide more Settings buttons if none have permissions. */
              if($methodName == 'more') continue;
          }
          else
          {
              $stepParams = $flow->type == 'table' ? "module={$flow->parent}" : "module={$flow->module}";
              if(isset($currentAction)) $stepParams .= "&action={$currentAction->action}";
          }

          if($moduleName == 'workflowfield' && $methodName == 'browse') $stepParams .= "&orderBy=order&groupID={$workflowGroupID}";

          $stepUrl     = $this->createLink($moduleName, $methodName, $stepParams);
          $stepUrlHtml = baseHTML::a($stepUrl, $label);

          if($currentModule == 'workflowfield' && $currentMethod == 'browse' && $flow->type == 'table')
          {
              $isCurrentStep = $moduleName == 'workflow' && $methodName == 'browsedb';
          }
          else
          {
              $isCurrentStep = (($currentModule == $moduleName && $currentMethod == $methodName) or (isset($subMenu[$currentModule]) && stripos(",{$subMenu[$currentModule]},", ",{$currentMethod},") !== false));
          }

          echo $isCurrentStep ? "<li class='active'>$stepUrlHtml</li>" : "<li>$stepUrlHtml</li>";

          if($isCurrentStep) $currentStep = $index;

          $stepUrls[] = $stepUrl;

          $index++;
      }
      ?>
    </ul>
  </nav>
  <div id='editorMenuLeft'>
    <?php if($currentStep > 0 && isset($stepUrls[$currentStep - 1])) echo baseHTML::a($stepUrls[$currentStep - 1], $lang->workfloweditor->prevStep, "class='btn btn-primary prevStep'"); ?>
  </div>
  <div id='editorMenuRight'>
    <?php
    if($editorMode == 'quick') echo "<button type='button' class='btn btn-default' id='saveBtn'>{$lang->save}</button>";
    if($currentStep == count($stepList) - 1 and !$flow->buildin and $workflowGroupID == 0)
    {
        echo html::a($this->createLink('workflow', 'release', "id={$flow->id}"), $lang->workflow->release, '', "class='btn btn-primary iframe' data-toggle='modal' data-size='sm'");
    }
    ?>
    <?php if(isset($stepUrls[$currentStep + 1])) echo baseHTML::a($stepUrls[$currentStep + 1], $lang->workfloweditor->nextStep, "class='btn btn-primary nextStep'"); ?>
  </div>
</div>
<?php if($editorMode == 'quick'): ?>
<div class='modal fade' id='confirmReleaseModal'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>×</span></button>
      </div>
      <div class='modal-body text-center'>
        <p><?php echo $lang->workfloweditor->confirmReleaseMessage; ?></p>
        <div>
          <?php extCommonModel::printLink('workflowfield', 'browse', "module={$flow->module}", $lang->workfloweditor->enterToAdvance, "class='btn btn-primary'");?>
          <?php extCommonModel::printLink('workflow', 'release', "id={$flow->id}", $lang->workfloweditor->continueRelease, "class='btn btn-secondary release' data-toggle='modal'");?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
