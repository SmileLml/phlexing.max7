<?php
/**
 * The edit view of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchplan
 * @version     $Id: edit.html.php 4129 2021-06-08 16:48:14Z
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->researchplan->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->researchplan->name;?></th>
            <td><?php echo html::input('name', $plan->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->customer;?></th>
            <td><?php echo html::input('customer', $plan->customer, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->stakeholder;?></th>
            <td><?php echo html::select('stakeholder[]', $users, $plan->stakeholder, "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->objective;?></th>
            <td><?php echo html::input('objective', $plan->objective, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->begin;?></th>
            <td><?php echo html::input("begin", helper::isZeroDate($plan->begin) ? '' : $plan->begin, "class='form-control form-datetime'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->end;?></th>
            <td><?php echo html::input("end", helper::isZeroDate($plan->end) ? '' : $plan->end, "class='form-control form-datetime'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->location;?></th>
            <td><?php echo html::input('location', $plan->location, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->team;?></th>
            <td><?php echo html::select('team[]', $insideUsers, $plan->team, "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->method;?></th>
            <td><?php echo html::select('method', $lang->researchplan->methodList, $plan->method, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->outline;?></th>
            <td colspan="3">
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=researchplan&link=outline');?>
              <?php echo html::textarea('outline', $plan->outline, 'row="6"');?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->schedule;?></th>
            <td colspan="3"><?php echo html::textarea('schedule', $plan->schedule, 'row="6"');?></td>
          </tr>
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
