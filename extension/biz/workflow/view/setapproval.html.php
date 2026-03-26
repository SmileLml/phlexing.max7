<?php include '../../workflow/view/header.html.php';?>
<?php include './coverconfirm.html.php';?>
<?php js::set('cover', $lang->workflow->cover);?>
<?php js::set('module', $flow->module);?>
<?php js::set('approvalCount', count($approvalFlows));?>
<div class='space space-sm'></div>
<div class='main-row'>
  <div class='side-col'>
    <?php include '../../workflow/view/side.html.php';?>
  </div>
  <div class='main-col'>
    <div class='panel'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflow->setApproval;?></strong>
      </div>
      <div class='panel-body'>
        <form id='setForm' method='post' action='<?php echo inlink('setApproval', "module=$flow->module");?>'>
          <?php if($module != 'charter'):?>
          <table class='table table-form' id='relationTable' style="width:<?php echo $lang->workflowrelation->tableWidth;?>px">
            <tbody>
              <tr>
                <th class='w-60px'><?php echo $lang->workflow->status;?></th>
                <td class='w-300px'><?php echo html::radio('approval', $lang->workflowapproval->approvalList, $flow->approval);?></td>
                <td></td>
              </tr>
              <?php if(!empty($approvalFlows)):?>
              <?php $approvalFlowID = current($approvalFlow);?>
              <tr class='approval-select hide'>
                <th><?php echo $lang->workflowapproval->approvalFlow;?></th>
                <td class='required'><?php echo html::select('approvalFlow', $approvalFlows, $approvalFlowID, "class='form-control chosen'");?></td>
                <td>
                    <?php if(common::hasPriv('approvalflow', 'design') && $approvalFlowID) echo html::a(helper::createLink('approvalflow' , 'design', "id=$approvalFlowID"), $lang->design->common, '', "class='btn btn-info designBtn'");?>
                    <?php if(common::hasPriv('approvalflow', 'create')) echo html::a(helper::createLink('approvalflow' , 'create', "workflow={$flow->module}"), $lang->create . $lang->approvalflow->common, '', "class='btn btn-info' data-toggle='modal' data-type='iframe'");?>
                </td>
              </tr>
              <?php endif;?>
              <tr class='submit-box'>
                <th></th>
                <td class='form-actions'><?php echo html::submitButton();?></td>
                <td></td>
              </tr>
            </tbody>
          </table>
          <?php if(empty($approvalFlows)):?>
          <div class='alert alert-warning approval-select hide'>
          <?php
            echo $lang->workflowapproval->noApproval;
            if(commonModel::hasPriv('approvalflow', 'browse'))
            {
                echo $lang->workflowapproval->createTips[0];
                echo baseHTML::a(helper::createLink('approvalflow', 'browse', 'type=workflow'), $lang->workflowapproval->createApproval, "target='_blank' class='btn btn-default margin-left'");
            }
            else
            {
                echo $lang->workflowapproval->createTips[1];
            }
          ?>
          </div>
          <?php endif;?>
          <?php else:?>
          <table class='table table-form' id='relationTable' style="width:<?php echo $lang->workflowrelation->tableWidth;?>px">
            <tbody>
              <tr>
                <th class='w-140px'><?php echo $lang->workflow->charterApprovalAction;?></th>
                <th class='w-200px text-left'><?php echo $lang->workflowapproval->approvalFlow;?></th>
                <td></td>
              </tr>
              <?php foreach($lang->workflow->charterApproval as $key => $label):?>
              <tr>
                <td class='text-right'><?php echo $label;?></td>
                <td class='required'><?php echo html::select("approvalFlow[$key]", $approvalFlows, $approvalFlow[$key], "class='form-control chosen disabled'");?></td>
                <td>
                    <?php if(common::hasPriv('approvalflow', 'design') && $approvalFlow[$key]) echo html::a(helper::createLink('approvalflow' , 'design', "id={$approvalFlow[$key]}"), $lang->design->common, '', "class='btn btn-info designBtn'");?>
                </td>
              </tr>
              <?php endforeach;?>
              <tr class='submit-box'>
                <th></th>
                <td class='form-actions'><?php echo html::submitButton();?></td>
                <?php echo html::hidden('approval', 'enabled');?>
                <td></td>
              </tr>
            </tbody>
          </table>
          <?php endif;?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
