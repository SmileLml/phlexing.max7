<?php
/**
 * The create of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: create.html.php 4903 2021-05-26 10:32:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->opportunity->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->opportunity->source;?></th>
            <td><?php echo html::select('source', $lang->opportunity->sourceList, '', "class='form-control chosen'");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
          </tr>
          <tr <?php if(empty($project->multiple)) echo "class='hidden'";?>>
            <th><?php echo $lang->opportunity->execution;?></th>
            <td><?php echo html::select('execution', $executions, isset($executionID) ? $executionID : '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->type;?></th>
            <td><?php echo html::select('type', $lang->opportunity->typeList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->strategy;?></th>
            <td><?php echo html::select('strategy', $lang->opportunity->strategyList, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->impact;?></th>
            <td><?php echo html::select('impact', $lang->opportunity->impactList, 3, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->chance;?></th>
            <td><?php echo html::select('chance', $lang->opportunity->chanceList, 3, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->ratio;?></th>
            <td><?php echo html::input('ratio', '', "class='form-control' readonly");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->pri;?></th>
            <td id='priValue'><?php echo html::select('pri', $lang->opportunity->priList, '', "class='form-control' disabled");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->identifiedDate;?></th>
            <td><?php echo html::input('identifiedDate', '', "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->plannedClosedDate;?></th>
            <td><?php echo html::input('plannedClosedDate', '', "class='form-control form-date'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->assignedTo;?></th>
            <td><?php echo html::select('assignedTo', $users, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->prevention;?></th>
            <td colspan='2'><?php echo html::textarea('prevention', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='3' class='form-actions text-center'>
              <?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
