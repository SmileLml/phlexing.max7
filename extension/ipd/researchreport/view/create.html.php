<?php
/**
 * The create view of researchreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     researchreport
 * @version     $Id: create.html.php 4129 2021-06-08 16:41:14Z
 * @link        https://www.zentao.net/
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->researchreport->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->researchreport->title;?></th>
            <td><?php echo html::input('title', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->author;?></th>
            <td><?php echo html::select('author', $users, $this->app->user->account, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->relatedPlan;?></th>
            <td><?php echo html::select('relatedPlan', $planPairs, empty($relatedPlan) ? '' : $relatedPlan, "class='form-control chosen' onchange='loadPlanInfo(this.value)'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->customer;?></th>
            <td><?php echo html::input('customer', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->researchObjects;?></th>
            <td><?php echo html::input('researchObjects', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->researchTime;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input("begin", '', "class='form-control form-datetime' placeholder='{$lang->researchreport->begin}'");?>
                <span class='input-group-addon fix-border'>~</span>
                <?php echo html::input("end", '', "class='form-control form-datetime' placeholder='{$lang->researchreport->end}'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->location;?></th>
            <td><?php echo html::input('location', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->method;?></th>
            <td><?php echo html::select('method', $lang->researchreport->methodList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->researchreport->content;?></th>
            <td colspan="3">
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=researchreport&link=content');?>
              <?php echo html::textarea('content', '', 'row="6"');?>
            </td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'>
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
