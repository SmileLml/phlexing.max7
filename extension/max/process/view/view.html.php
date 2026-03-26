<?php
/**
 * The details view of process module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     process
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php
$browseLink = $this->createLink('process', 'browse' , "browseType=$process->model");
$createLink = $this->createLink('process', 'create');

$dateFiled  = array('deadline', 'resolvedDate', 'createdDate', 'editedDate', 'activateDate', 'closedDate', 'assignedDate');
foreach($process as $field => $value)
{
    if(in_array($field, $dateFiled) && strpos($value, '0000') === 0) $process->$field = '';
}
?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $process->id?></span>
      <span class="text" title="<?php echo $process->name?>"><?php echo $process->name?></span>
    </div>
  </div>
</div>
<div class="main-row" id="mainContent">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->process->desc;?></div>
        <div class="detail-content article-content"><?php echo $process->desc;?></div>
      </div>
    </div>
    <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=process&objectID=$process->id");?>
    <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php common::printBack($browseLink);?>
        <?php if(!isonlybody()) echo "<div class='divider'></div>";?>
        <?php if(!$process->deleted):?>
        <?php
        common::printIcon('activity', 'batchCreate', "processID=$process->id", $process, 'button', 'treemap-alt', '', '', '', '', $lang->process->createActivity);
        common::printIcon('process', 'activityList', "processID=$process->id", $process, 'button', 'list-alt', '', 'iframe showinonlybody', 'yes', '', $lang->process->activityList);
        echo "<div class='divider'></div>";
        common::printIcon('process', 'edit', "processID=$process->id", $process);
        common::printIcon('process', 'delete', "processID=$process->id", $process, 'button', 'trash', 'hiddenwin');
        ?>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
    <div class="panel-heading"><strong><?php echo $lang->process->basicInfo;?></strong></div>
      <table class="table table-data">
        <tbody>
          <tr valign="middle">
            <th class="thWidth w-100px"><?php echo $lang->process->id;?></th>
            <td><?php echo $process->id;?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->type;?></th>
            <td><?php echo zget($lang->process->$classify, $process->type);?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->abbr;?></th>
            <td><?php echo $process->abbr;?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->createdBy;?></th>
            <td><?php echo zget($users, $process->createdBy);?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->createdDate;?></th>
            <td><?php echo $process->createdDate;?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->editedBy;?></th>
            <td><?php echo zget($users, $process->editedBy);?></td>
          </tr>
          <tr valign="middle">
            <th class="thWidth w-80px"><?php echo $lang->process->editedDate;?></th>
            <td><?php echo $process->editedDate;?></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
