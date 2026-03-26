<?php
/**
 * The view file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     business(商业软件)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     company
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
if($iframe == 'yes')
{
    $type = 'effort';
    die(include './showdata.html.php');
}
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datatable.html.php';?>
<?php js::set('maxCount', $config->maxCount);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    foreach($lang->company->featureBar['calendar'] as $type => $name)
    {
        $activeClass = $type == 'calendar' ? ' btn-active-text' : '';
        echo html::a(inlink($type == 'calendar' ? 'calendar' : 'effort', $type == 'calendar' ? '' : "date=$type"), "<span class='text'>{$name}</span>", '', "class='btn btn-link{$activeClass}' id='{$type}Tab'");
    }
    ?>
  </div>
  <div class='btn-toolbar pull-right'><?php common::printIcon('effort', 'export', "userID=$userID&orderBy=date_asc", '', 'button', '', '', 'export', false, "data-group='admin'");?></div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <form method='post' target='hiddenwin' action='<?php echo $this->createLink('company', 'calendar');?>'>
        <div class='detail'>
          <div class='detail-content'>
            <div class='form-group'>
              <div class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->userType;?></div></span>
                <?php echo html::select('userType', $lang->company->userTypes, $userType, "class='form-control chosen'");?>
              </div>
            </div>
            <div class='form-group'>
              <div class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->dept;?></div></span>
                <?php if(empty($mainDepts)) $mainDepts = array(0 => '/');?>
                <?php echo html::select('dept', $mainDepts, $parent, "class='form-control chosen' onchange='loadDeptUsers(this.value)'");?>
              </div>
            </div>
            <div class='form-group'>
              <div id='userBox' class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->user;?></div></span>
                <?php echo html::select('user', $users, $userID, 'class="form-control chosen"');?>
              </div>
            </div>
            <div class='form-group'>
              <div class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->beginDate;?></div></span>
                <?php echo html::input('begin', $begin, 'class="form-control form-date"');?>
              </div>
            </div>
            <div class='form-group'>
              <div class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->endDate;?></div></span>
                <?php echo html::input('end', $end, 'class="form-control form-date"');?>
              </div>
            </div>
            <div class='form-group'>
              <div id='productIdBox' class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->product;?></div></span>
                <?php if($config->vision == 'lite'):?>
                <?php echo html::select('product', $products, $product, 'class="form-control chosen" onchange="loadProductExecutions(this.value)"');?>
                <?php else:?>
                <?php echo html::select('product', $products, $product, 'class="form-control chosen" onchange="loadProductProject(this.value)"');?>
                <?php endif;?>
              </div>
            </div>
            <?php if($config->vision != 'lite'):?>
            <div class='form-group'>
              <div id='projectIdBox' class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->project;?></div></span>
                <?php echo html::select('project', $projects, $project, 'class="form-control chosen" onchange="loadProductExecutions($(\'#product\').val(), this.value)"');?>
              </div>
            </div>
            <?php endif;?>
            <div class='form-group'>
              <div id='executionIdBox' class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $config->vision != 'lite' ? $lang->execution->common : $lang->company->execution;?></div></span>
                <?php echo html::select('execution', $executions, $execution, 'class="form-control chosen"');?>
              </div>
            </div>
            <div class='form-group'>
              <div id='showUserBox' class='input-group'>
                <span class='input-group-addon'><div class='w-60px'><?php echo $lang->company->show;?></div></span>
                <?php echo html::select('showUser', $lang->company->showUsers, $showUser, 'class="form-control chosen"');?>
              </div>
            </div>
            <div class='form-group'><?php echo html::submitButton($lang->company->effort->view);?></div>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="main-col cell">
    <div id='showdata' data-url='<?php echo $this->createLink('company', 'calendar', "dept=$parent&begin=" . strtotime($begin) . "&end=" . strtotime($end) . "&product=$product&project=$project&execution=$execution&userID=$userID&showUser=$showUser&iframe=yes&userType=$userType")?>'>
      <div style='padding: 40px;' class='text-center'><i class='icon-spinner icon-spin' style='font-size: 28px'></i></div>
    </div>
  </div>
</div>
<script>
$(function()
{
    if($('#user').val())
    {
        $('#showUser_chosen').css('pointer-events', 'none')
        $('#showUser_chosen').children('.chosen-single').addClass('grey');
    }
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
