<?php
/**
 * The edit of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: edit.html.php 4903 2021-05-27 10:32:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class='main-header'>
      <h2><?php echo $lang->assetlib->editOpportunity;?></small></h2>
    </div>
    <form method='post' class="main-form form-ajax" enctype='multipart/form-data' id='opportunityForm'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->opportunity->name;?></th>
            <td><?php echo html::input('name', $opportunity->name, "class='form-control' required");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->source;?></th>
            <td><?php echo html::select('source', $lang->opportunity->sourceList, $opportunity->source, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->type;?></th>
            <td><?php echo html::select('type', $lang->opportunity->typeList, $opportunity->type, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->strategy;?></th>
            <td><?php echo html::select('strategy', $lang->opportunity->strategyList, $opportunity->strategy, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->impact;?></th>
            <td><?php echo html::select('impact', $lang->opportunity->impactList, $opportunity->impact, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->chance;?></th>
            <td><?php echo html::select('chance', $lang->opportunity->chanceList, $opportunity->chance, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->ratio;?></th>
            <td><?php echo html::input('ratio', $opportunity->ratio, "class='form-control' readonly");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->pri;?></th>
            <td id='priValue'><?php echo html::select('pri', $lang->opportunity->priList, $opportunity->pri, "class='form-control' disabled");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->desc;?></th>
            <td colspan="2"><?php echo html::textarea('desc', $opportunity->desc, 'row="6"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->prevention;?></th>
            <td colspan="2"><?php echo html::textarea('prevention', $opportunity->prevention, 'row="6"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->opportunity->resolution;?></th>
            <td colspan="2"><?php echo html::textarea('resolution', $opportunity->resolution, 'row="6"');?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
