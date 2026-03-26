<?php
/**
 * The batchcreate of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: batchcreate.html.php 4903 2021-06-08 15:13:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('middleName', $lang->opportunity->priList['middle']);?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->opportunity->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
    </div>
  </div>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='batchCreateForm'>
    <table class="table table-form">
      <?php $hiddenExecution = empty($project->multiple) ? 'hidden' : '';?>
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->opportunity->id;?></th>
          <th class='w-200px <?php echo $hiddenExecution;?>'><?php echo $lang->opportunity->execution;?></th>
          <th class='required'><?php echo $lang->opportunity->name;?></th>
          <th class='w-200px'><?php echo $lang->opportunity->source;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->impact;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->chance;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->ratio;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->pri;?></th>
          <th class='w-150px'><?php echo $lang->opportunity->desc;?></th>
        </tr>
      </thead>
      <tbody>
        <?php for($i = 0; $i < $config->opportunity->batchCreate; $i ++):?>
        <tr>
          <td><?php echo $i + 1;?></td>
          <td <?php echo "class='{$hiddenExecution}'";?>><?php echo html::select("execution[$i]", $executions, isset($executionID) ? $executionID : '', "class='form-control chosen'");?></td>
          <td><?php echo html::input("name[$i]", '', "class='form-control title-import'");?></td>
          <td><?php echo html::select("source[$i]", $lang->opportunity->sourceList, '', "class='form-control chosen'");?></td>
          <td><?php echo html::select("impact[$i]", $lang->opportunity->impactList, 3, "class='form-control' data-number=$i onchange='computeIndex(this, $i)'");?></td>
          <td><?php echo html::select("chance[$i]", $lang->opportunity->chanceList, 3, "class='form-control' data-number=$i onchange='computeIndex(this, $i)'");?></td>
          <td><?php echo html::input("ratio[$i]", '', "class='form-control' readonly id='ratio$i'");?></td>
          <td id="priValue<?php echo $i;?>"><?php echo html::select("pri[$i]", $lang->opportunity->priList, '', "class='form-control' disabled");?></td>
          <td><?php echo html::textarea("desc[$i]", '', "rows='1' class='form-control autosize'");?></td>
        </tr>
        <?php endfor;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='8' class='form-actions text-center'>
            <?php echo html::submitButton() . html::backButton($lang->goback, "data-app='{$app->tab}'");?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<table class='template' id='trTemp'>
  <tbody>
    <tr>
      <td>%s</td>
      <td <?php echo "class='{$hiddenExecution}'";?>><?php echo html::select("execution[%s]", $executions, '', "class='form-control chosen' data-number=%s onchange='computeIndex(this, \"%s\")'");?></td>
      <td><?php echo html::input("name[%s]", '', "class='form-control title-import'");?></td>
      <td><?php echo html::select("source[%s]", $lang->opportunity->sourceList, '', "class='form-control chosen'");?></td>
      <td><?php echo html::select("impact[%s]", $lang->opportunity->impactList, 3, "class='form-control' data-number=%s onchange='computeIndex(this, \"%s\")'");?></td>
      <td><?php echo html::select("chance[%s]", $lang->opportunity->chanceList, 3, "class='form-control' data-number=%s onchange='computeIndex(this, \"%s\")'");?></td>
      <td><?php echo html::input("ratio[%s]", 9, "class='form-control' readonly id='ratio%s'");?></td>
      <td id="priValue%s"><?php echo html::select("pri[%s]", $lang->opportunity->priList, 'middle', "class='form-control chosen' disabled");?></td>
    </tr>
  </tbody>
</table>
<?php include $app->getModuleRoot() . 'common/view/pastetext.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
