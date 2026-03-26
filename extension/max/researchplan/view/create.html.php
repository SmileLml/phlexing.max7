<?php
/**
 * The create view of researchplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchplan
 * @version     $Id: create.html.php 4129 2021-06-08 16:41:14Z
 * @link        https://www.zentao.net/
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->researchplan->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->researchplan->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->customer;?></th>
            <td><?php echo html::input('customer', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->stakeholder;?></th>
            <td><?php echo html::select('stakeholder[]', $users, '', "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->objective;?></th>
            <td><?php echo html::input('objective', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->begin;?></th>
            <td><?php echo html::input("begin", '', "class='form-control form-datetime'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->end;?></th>
            <td><?php echo html::input("end", '', "class='form-control form-datetime'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->location;?></th>
            <td><?php echo html::input('location', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->team;?></th>
            <td><?php echo html::select('team[]', $insideUsers, '', "class='form-control chosen' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->method;?></th>
            <td><?php echo html::select('method', $lang->researchplan->methodList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->outline;?></th>
            <td colspan="3">
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=researchplan&link=outline');?>
              <?php echo html::textarea('outline', '', "row='6' class='form-control kindeditor disabled-ie-placeholder' hidefocus='true'");?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->researchplan->schedule;?></th>
            <td colspan="3"><?php echo html::textarea('schedule', '', 'row="6"');?></td>
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
