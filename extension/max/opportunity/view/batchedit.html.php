<?php
/**
 * The batchedit of opportunity module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     opportunity
 * @version     $Id: batchcreate.html.php 4903 2021-06-09 09:13:59Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->opportunity->batchEdit;?></h2>
  </div>
  <form class="main-form" method='post' target='hiddenwin' id='batchEditForm' action="<?php echo inLink('batchEdit', "projectID=$projectID&from=$from");?>">
    <table class="table table-form">
      <thead>
        <tr>
          <th class='w-50px'><?php echo $lang->opportunity->id;?></th>
          <th class='required'><?php echo $lang->opportunity->name;?></th>
          <th class='w-200px'><?php echo $lang->opportunity->source;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->impact;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->chance;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->ratio;?></th>
          <th class='w-90px'><?php echo $lang->opportunity->pri;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($opportunities as $opportunityID => $opportunity):?>
        <tr>
          <td><?php echo $opportunityID . html::hidden("opportunityIDList[$opportunityID]", $opportunityID);?></td>
          <td><?php echo html::input("names[$opportunityID]", $opportunity->name,  "class='form-control'");?></td>
          <td><?php echo html::select("sources[$opportunityID]", $lang->opportunity->sourceList, $opportunity->source, "class='form-control chosen'");?></td>
          <td><?php echo html::select("impact[$opportunityID]", $lang->opportunity->impactList, $opportunity->impact, "class='form-control' data-number=$opportunityID");?></td>
          <td><?php echo html::select("chance[$opportunityID]", $lang->opportunity->chanceList, $opportunity->chance, "class='form-control' data-number=$opportunityID");?></td>
          <td><?php echo html::input("ratio[$opportunityID]", $opportunity->ratio, "class='form-control' readonly id='ratio$opportunityID'");?></td>
          <td id="priValue<?php echo $opportunityID;?>"><?php echo html::select("pri[$opportunityID]", $lang->opportunity->priList, $opportunity->pri, "class='form-control' disabled");?></td>
        </tr>
        <?php endforeach;?>
        <tr>
          <td colspan='7' class='form-actions text-center'>
            <?php echo html::submitButton() .  html::linkButton($lang->goback, $this->createLink('opportunity', 'browse', "projectID=$projectID&from=$from"), 'self', '', 'btn btn-wide');?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
