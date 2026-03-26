<?php
/**
 * The report view file of flow module of ZDOO.
 *
 * @copyright   Copyright 2009-2020 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Dongdong Jia <jiadongdong@easycorp.ltd>
 * @package     flow
 * @version     $Id$
 * @link        http://www.zdoo.com
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('moduleName', $flow->module);?>
<?php js::set('buildin', $flow->buildin);?>
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
        <?php $canvasID = "chart-{$chart->id}";?>
        <tr class='text-top'>
          <td>
            <div class='chart-wrapper text-center' style="position: relative;">
              <h5><?php echo $chart->name;?></h5>
              <div class='chart-canvas'>
                <?php if($chart->type != 'pie'):?>
                <div class="legend <?php echo $chart->type;?>-legend"></div>
                <?php endif;?>
                <canvas id='<?php echo $canvasID;?>' data-type='<?php echo $chart->type;?>' data-legend='<?php echo $chart->legend;?>' data-displaytype='<?php echo $chart->displayType;?>' width='<?php echo $config->flow->report->width;?>' height='<?php echo $config->flow->report->height;?>' data-responsive='true'></canvas>
              </div>
              <?php if($chart->type == 'pie'):?>
              <div class="legend pie-legend scrollbar-hover"></div>
              <?php endif;?>
            </div>
            <div class='side-col col-xl hidden'>
              <div style="overflow:auto;" class='table-wrapper'>
                <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chart->type; ?>' data-target='#<?php echo $canvasID?>' data-animation='false'>
                  <?php foreach($chartData[$canvasID] as $key => $data):?>
                  <tr>
                    <td class='chart-color'><i class='chart-color-dot'></i></td>
                    <td class='chart-label text-left' title='<?php echo $data->label;?>'><?php echo $data->label;?></td>
                    <td class='chart-value text-right'><?php echo $data->value;?></td>
                  </tr>
                  <?php endforeach;?>
                </table>
              </div>
            </div>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
