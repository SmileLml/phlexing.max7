<?php
/**
 * The finish view file of deploy module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     deploy
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('showService', !empty($services));?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $deploy->name . $lang->hyphen . $lang->deploy->finish;?></h2>
  </div>
  <form method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='w-100px'><?php echo $lang->deploy->result?></th>
        <td class='w-p30'><?php echo html::select('result', $lang->deploy->resultList, '', "class='form-control'")?></td>
        <td></td>
      </tr>
      <tr id='serviceBox' class='hide'>
        <th><?php echo $lang->deploy->service?></th>
        <td colspan='2'>
          <table class='table table-fixed with-border'>
            <thead>
              <tr>
                <th class='w-id'><?php echo $lang->idAB?></th>
                <th><?php echo $lang->service->name?></th>
                <th class='w-120px'><?php echo $lang->service->version?></th>
                <th><?php echo $lang->service->softName?></th>
                <th class='w-120px'><?php echo $lang->service->softVersion?></th>
                <th class='w-90px'><?php echo $lang->deploy->updateHost?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($services as $service):?>
              <tr>
                <td class='text-center'><?php echo $service->id?></td>
                <?php $serviceName = isset($optionMenu[$service->id]) ? $optionMenu[$service->id] : $service->name?>
                <td title='<?php echo $serviceName?>'><?php echo $serviceName?></td>
                <td><?php echo html::input("version[$service->id]", $service->version, "class='form-control'")?></td>
                <td title='<?php echo $service->softName?>'><?php echo html::input("softName[$service->id]", $service->softName, "class='form-control'")?></td>
                <td><?php echo html::input("softVersion[$service->id]", $service->softVersion, "class='form-control'")?></td>
                <td class='text-center updateHost'>
                  <label class="checkbox-inline">
                    <input name="updateHost[<?php echo $service->id?>]" value="1" checked="checked" type="checkbox">
                  </label>
                </td>
              </tr>
              <?php endforeach?>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->comment?></th>
        <td colspan='2'><?php echo html::textarea('comment', '', "class='form-control'")?></td>
      </tr>
      <tr>
        <td colspan='3' class='text-center form-actions'>
          <?php echo html::submitButton();?>
          <?php echo html::backButton();?>
        </td>
      </tr>
    </table>
  </form>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
