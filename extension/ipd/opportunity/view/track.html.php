<?php
/**
 * The track of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: track.html.php 4903 2021-05-27 13:45:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->opportunity->track;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->opportunity->isChange;?></th>
            <td><?php echo html::radio('isChange', $lang->opportunity->isChangeList, 0, "onclick=refreshPage(this)");?></td>
            <td></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->name;?></th>
            <td><?php echo html::input('name', $opportunity->name, "class='form-control'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->type;?></th>
            <td><?php echo html::select('type', $lang->opportunity->typeList, $opportunity->type, "class='form-control chosen'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->strategy;?></th>
            <td><?php echo html::select('strategy', $lang->opportunity->strategyList, $opportunity->strategy, "class='form-control chosen'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->impact;?></th>
            <td><?php echo html::select('impact', $lang->opportunity->impactList, $opportunity->impact, "class='form-control chosen'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->chance;?></th>
            <td><?php echo html::select('chance', $lang->opportunity->chanceList, $opportunity->chance, "class='form-control chosen'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->ratio;?></th>
            <td><?php echo html::input('ratio', $opportunity->ratio, "class='form-control' readonly");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->pri;?></th>
            <td id='priValue'><?php echo html::select('pri', $lang->opportunity->priList, $opportunity->pri, "class='form-control chosen' disabled");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->lastCheckedBy;?></th>
            <td><?php echo html::select('lastCheckedBy', $users, $this->app->user->account, "class='form-control chosen'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->lastCheckedDate;?></th>
            <td><?php echo html::input('lastCheckedDate', helper::isZeroDate($opportunity->lastCheckedDate) ? helper::today() : $opportunity->lastCheckedDate , "class='form-control form-datetime'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->desc;?></th>
            <td colspan='2'><?php echo html::textarea('desc', $opportunity->desc, "class='form-control'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->prevention;?></th>
            <td colspan='2'><?php echo html::textarea('prevention', $opportunity->prevention, "class='form-control'");?></td>
          </tr>
          <tr class='track hidden'>
            <th><?php echo $lang->opportunity->resolution;?></th>
            <td colspan='2'><?php echo html::textarea('resolution', $opportunity->resolution, "class='form-control'");?></td>
          </tr>
          <tr class='not-track'>
            <th><?php echo $lang->comment;?></th>
            <td colspan='2'><?php echo html::textarea('comment', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan='3' class='form-actions text-center'>
              <?php echo html::submitButton() . html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
