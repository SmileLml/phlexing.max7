<?php
/**
 * The custom view file of report module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2014 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     report
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('type', $type);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php $browseLink = $this->session->reportList ? $this->session->reportList : inlink('browseReport', "");?>
    <?php common::printBack($browseLink, 'btn btn-primary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <?php $title = empty($step) ? $lang->crystal->custom : $lang->report->useReportAction;?>
      <span title='<?php echo $title;?>' class='text'><?php echo $title;?></span>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <!-- SQL查询 -->
  <div class='center-block'><?php include 'blocksql.html.php'?></div>
  <?php if(!empty($dataList) and !empty($report)):?>
  <!-- 报表条件 -->
  <div class='center-block'><?php include 'blockcondition.html.php'?></div>
  <?php endif;?>
  <?php if(!empty($dataList)):?>
  <!-- 显示结果 -->
  <div class='center-block result'><?php include 'blockdata.html.php'?></div>
  <?php else:?>
  <div class='center-block result'>
    <div class='panel'>
      <div class='panel-heading'>
        <div class='panel-title'><?php echo $lang->crystal->result?></div>
      </div>
      <div class='panel-body'><?php echo $lang->error->noData?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php js::set('reportID', $reportID)?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
