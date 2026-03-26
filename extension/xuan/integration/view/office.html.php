<?php
/**
 * The office view file of integration module of XXB.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd., www.zentao.net)
 * @license     ZOSL (https://zpl.pub/page/zoslv1.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     integration
 * @version     $Id$
 * @link        https://xuanim.com
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->integration->office;?></strong>
  </div>
  <form method="post" class="form-ajax" id="office-ajax-form">
    <table class="table table-form">
      <tr>
        <th class="w-120px"><?php echo $lang->integration->switch?></th>
        <td class='w-p50'><?php echo html::radio('officeEnabled', $lang->integration->switchList, zget(isset($config->integration->office) ? $config->integration->office : '', 'officeEnabled'));?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->integration->type?></th>
        <td><?php echo $lang->integration->collabora?></td>
      </tr>
      <tr class='collaboraBox'>
        <th><?php echo $lang->integration->collaboraPath?></th>
        <td><?php echo html::input('collaboraPath', zget(isset($config->integration->office) ? $config->integration->office : '', 'collaboraPath', ''), "class='form-control' autocomplete='off' placeholder='{$lang->integration->placeholder->collabora}'")?></td>
      </tr>
      <tr>
        <th></th>
        <td colspan='2'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function()
{
  $.setAjaxForm('#office-ajax-form');
  $('[name=officeEnabled]').change(function()
  {
      if($(this).prop('checked'))
      {
          if($(this).val() == '1')
          {
              $('.collaboraBox').removeClass('hidden');
          }
          else
          {
              $('.collaboraBox').addClass('hidden');
          }
      }
  });
  $('[name=officeEnabled]').change();
});
</script>
<?php include '../../common/view/footer.html.php';?>
