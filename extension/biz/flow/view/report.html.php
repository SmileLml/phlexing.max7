<?php
/**
 * The report view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd> 
 * @package     flow 
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chart.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('buildin', $flow->buildin);?>
<?php js::set('chartData', $chartData);?>
<div class='main-row'>
  <div class='side-col col-3'>
    <div class='panel panel-sm'>
      <div class='panel-heading'><strong><?php echo $lang->workflowreport->select;?></strong></div>
      <div class='panel-body'>
        <form method='post' action='<?php echo $this->createLink($flow->module, 'report');?>'>
          <?php echo html::checkBox('reports', $reportPairs, $checkedReports, '', 'block');?>
          <p><?php echo html::selectAll() . html::submitButton($lang->workflowreport->generate);?></p>
        </form>
      </div>
    </div>
  </div>
  <div class='main-col col-9'>
    <div class='panel panel-sm'>
      <div class='panel-heading'>
        <strong><?php echo $lang->workflowreport->report;?></strong>
        <span class='text-warning'><?php echo $lang->workflowreport->tips->source;?></span>
      </div>
      <table class='table active-disabled'>
        <?php foreach($charts as $chart):?>
        <tr class='text-top'>
          <td>
            <div class='chart-wrapper text-center' style="position: relative;">
              <h5><?php echo $chart->name;?></h5>
              <div class='chart-canvas'>
                <?php if($chart->type != 'pie'):?>
                <div class="legend <?php echo $chart->type;?>-legend"></div>
                <?php endif;?>
                <canvas id='chart-<?php echo $chart->id;?>' data-type='<?php echo $chart->type;?>' data-legend='<?php echo $chart->legend;?>' data-displaytype='<?php echo $chart->displayType;?>' width='<?php echo $config->flow->report->width;?>' height='<?php echo $config->flow->report->height;?>' data-responsive='true'></canvas>
              </div>
              <?php if($chart->type == 'pie'):?>
              <div class="legend pie-legend scrollbar-hover"></div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
