<?php
/**
 * The view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Deqing Chai <chaideqing@easycorp.ltd>
 * @package   marketresearch
 * @version   $Id: create.html.php 4808 2023-08-28 09:48:13Z zhujinyonging@gmail.com $
 * @link      http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('longTime', $lang->marketresearch->longTime);?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->marketresearch->create;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->marketresearch->name;?></th>
            <td><?php echo html::input('name', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->market;?></th>
            <td id='marketBox' class='required'>
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
                <?php echo html::select('market', $marketList, $marketID, "class='form-control picker-select'");?>
                <?php endif;?>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->PM;?></th>
            <td><?php echo html::select('PM', $users, '', "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th id="dateRange"><?php echo $lang->marketresearch->dateRange;?></th>
            <td>
              <div id='dateBox' class='input-group'>
                <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->marketresearch->begin . "' required");?>
                <span class='input-group-addon'><?php echo $lang->marketresearch->to;?></span>
                <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->marketresearch->end . "' required");?>
              </div>
            </td>
            <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->marketresearch->endList, '', "onclick='computeEndDate(this.value)'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->desc;?></th>
            <td colspan='3'><?php echo html::textarea('desc', '', "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->acl;?></th>
            <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->marketresearch->aclList, 'private', "onclick='setWhite(this.value);'", 'block'));?></td>
          </tr>
          <tr id="whitelistBox">
            <th><?php echo $lang->whitelist;?></th>
            <td colspan='2'>
              <div class='input-group'>
                <?php echo html::select('whitelist[]', $users, '', 'class="form-control picker-select" multiple');?>
                <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
              </div>
            </td>
          </tr>
          <tr>
            <td class='form-actions text-center' colspan='4'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
