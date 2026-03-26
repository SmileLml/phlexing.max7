<?php
/**
 * The view file of marketreport module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Deqing Chai <chaideqing@easycorp.ltd>
 * @package   marketreport
 * @version   $Id: create.html.php 4808 2023-08-28 09:48:13Z zhujinyonging@gmail.com $
 * @link      http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->marketreport->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->marketreport->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control' required");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketreport->source;?></th>
            <td><?php echo nl2br(html::radio('source', $lang->marketreport->sourceList, 'inside', '', ''));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketreport->market;?></th>
            <td id='marketBox' class=<?php echo empty($marketID) ? '' : 'disabled'?>>
                <?php if(empty($marketID)):?>
                <div class='input-group '>
                    <?php echo html::select('market', $marketList, $marketID, "class='form-control picker-select'");?>
                    <?php echo html::input('marketName', '', "class='form-control newMarket' style='display:none'");?>
                    <?php if(common::hasPriv('market', 'create')):?>
                    <span class='input-group-addon newMarket'>
                      <?php echo html::checkBox('newMarket', $lang->market->create, '', "onchange=addNewMarket();");?>
                    </span>
                    <?php endif;?>
                </div>
                <?php else:?>
                <?php echo html::select('market', $marketList, $marketID, "class='form-control picker-select' disabled");?>
                <?php endif;?>
            </td>
          </tr>
          <tr class='showInside'>
            <th><?php echo $lang->marketreport->research;?></th>
            <td><?php echo html::select('research', $researchList, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr class='showInside'>
            <th><?php echo $lang->marketreport->owner;?></th>
            <td><?php echo html::select('owner', $users, $app->user->account, "class='form-control picker-select'");?></td>
          </tr>
          <tr class='showInside'>
            <th><?php echo $lang->marketreport->participants;?></th>
            <td><?php echo html::select('participants[]', $users, $app->user->account, "class='form-control picker-select' multiple");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketreport->desc;?></th>
            <td colspan='3'><?php echo html::textarea('desc', '', "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketreport->files;?></th>
            <td colspan='3'>
              <?php
              echo $this->fetch('file', 'buildoldform');
              ?>
            </td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='3'>
            <?php echo html::commonButton($lang->marketreport->save, "id='saveButton'", 'btn btn-primary btn-wide');?>
            <?php echo html::commonButton($lang->marketreport->saveDraft, "id='saveDraftButton'", 'btn btn-secondary btn-wide');?>
            <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
