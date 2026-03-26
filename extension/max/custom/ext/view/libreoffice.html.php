<?php
/**
 * The libreoffice view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->custom->libreOffice?></h2>
  </div>
  <form method="post" target="hiddenwin">
  <table class="table table-form">
    <tr>
      <th class="c-name"><?php echo $lang->custom->libreOfficeTurnon?></th>
      <td class='c-value'><?php echo html::radio('libreOfficeTurnon', $lang->custom->turnonList, $config->file->libreOfficeTurnon);?></td>
    </tr>
    <tr>
      <th><?php echo $lang->custom->type?></th>
      <?php if($config->requestType != 'PATH_INFO') unset($lang->custom->typeList['collabora']);?>
      <td><?php echo html::radio('convertType', $lang->custom->typeList, zget($config->file, 'convertType', 'libreoffice'))?></td>
    </tr>
    <tr class='libreofficeBox'>
      <th><?php echo $lang->custom->libreOfficePath?></th>
      <td><?php echo html::input('sofficePath', zget($config->file, 'sofficePath', ''), "class='form-control' autocomplete='off' placeholder='{$lang->custom->sofficePlaceholder}'")?></td>
    </tr>
    <tr class='collaboraBox hidden'>
      <th><?php echo $lang->custom->collaboraPath?></th>
      <td><?php echo html::input('collaboraPath', zget($config->file, 'collaboraPath', ''), "class='form-control' autocomplete='off' placeholder='{$lang->custom->collaboraPlaceholder}'")?></td>
    </tr>
    <tr>
      <td colspan='2' class='text-center form-actions'><?php echo html::submitButton();?></td>
    </tr>
  </table>
  </form>
</div>
<script>
$(function()
{
    $('#mainHeader #navbar li[data-id=custom]').removeClass('active');
    $('#mainHeader #navbar li[data-id=model]').removeClass('active');
    $(document).on('change', '[name=convertType]', function()
    {
        if($(this).prop('checked'))
        {
            if($(this).val() == 'libreoffice')
            {
                $('.libreofficeBox').removeClass('hidden');
                $('.collaboraBox').addClass('hidden');
            }
            else
            {
                $('.libreofficeBox').addClass('hidden');
                $('.collaboraBox').removeClass('hidden');
            }
        }
    });
    $('[name=convertType]').trigger('change');
})
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
