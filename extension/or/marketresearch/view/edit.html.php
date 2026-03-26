<?php
/**
 * The edit view file of marketresearch module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Hu Fangzhou <hufangzhou@easycorp.ltd>
 * @package   marketresearch
 * @link      https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('longTime', $lang->marketresearch->longTime);?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('researchAcl', $research->acl);?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->marketresearch->edit;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-140px'><?php echo $lang->marketresearch->name;?></th>
            <td><?php echo html::input('name', $research->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->market;?></th>
            <td id='marketBox' class='required'><?php echo html::select('market', $marketList, $research->market, "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->PM;?></th>
            <td><?php echo html::select('PM', $users, $research->PM, "class='form-control picker-select'");?></td>
          </tr>
          <tr>
            <th id="dateRange"><?php echo $lang->marketresearch->dateRange;?></th>
            <td>
              <div id='dateBox' class='input-group'>
                <?php echo html::input('begin', $research->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->marketresearch->begin . "' required");?>
                <span class='input-group-addon'><?php echo $lang->marketresearch->to;?></span>
                <?php echo html::input('end', $research->end, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->marketresearch->end . "' required");?>
              </div>
            </td>
            <?php
            /* Remove LONG_TIME item when no multiple project. */
            if(empty($research->multiple) && $research->end != LONG_TIME) unset($lang->marketresearch->endList[999]);
            $deltaValue = $research->end == LONG_TIME ? 999 : (strtotime($research->end) - strtotime($research->begin)) / 3600 / 24 + 1;
            ?>
            <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->marketresearch->endList, $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->desc;?></th>
            <td colspan='3'><?php echo html::textarea('desc', $research->desc, "class='form-control kindeditor'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->marketresearch->acl;?></th>
            <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->marketresearch->aclList, $research->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
          </tr>
          <tr id="whitelistBox">
            <th><?php echo $lang->whitelist;?></th>
            <td colspan='2'>
              <div class='input-group'>
                <?php echo html::select('whitelist[]', $users, $research->whitelist, 'class="form-control picker-select" multiple');?>
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
