<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$initItem  = html::select('unit[]', $versionForm, 0, 'class="form-control unit-col-7" onchange="changeOption(this)"');
$initItem .= html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"');
$initItem .= html::select('unit[]', $joint, 1, 'class="form-control unit-col-3 border-left" onchange="change()"');
$initItem .= html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"');
$itemRow = <<<EOT
<tr class="text-center">
  <td class='unitBox'>
    {$initItem}
  </td>
  <td class="c-actions">
    <a href="javascript:void(0)" onclick="addOptions(this)" class="btn btn-link"><i class="icon-plus"></i></a>
    <a href="javascript:void(0)" onclick="delOptions(this)" class="btn btn-link"><i class="icon-close"></i></a>
  </td>
</tr>
EOT;
?>
<?php js::set('itemRow', $itemRow)?>
<?php js::set('user', $this->app->user->account)?>
<?php $unitGroup = ''?>
<style>.versionCase{color: #449f00;}</style>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      if($object)
      {
          echo zget($objectType, $object);
          echo html::a(inlink($method, "type=$type&object="), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      else
      {
          echo $lang->reviewcl->browseByType;
      }
      ?>
    </div>
  </div>
  <div class='bg-white'>
    <?php include '../../reviewcl/view/menu.html.php';?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col" id='sidebar'>
    <div class="cell">
      <div class="list-group">
        <?php foreach ($objectType as $key => $obj):?>
          <?php if(empty($key) or empty($obj)) continue;?>
          <?php $key == $object ? $active = 'class="active"' : $active = '';?>
          <?php echo html::a($this->createLink('reviewsetting', $method, "type=$type&object=$key"), $obj, '', $active);?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="main-content">
      <form class="load-indicator main-form form-ajax" method="post">
        <div class="main-header">
          <div class="heading">
            <strong><?php echo $lang->reviewsetting->setting . zget($objectType, $object) . $lang->reviewsetting->version;?></strong>
          </div>
        </div>
        <table class="table table-form active-disabled table-condensed mw-800px version-box">
          <tbody>
          <?php if($result):?>
          <?php if($result->unit):?>
            <?php foreach ($result->unit as $unit):?>
              <tr class="text-center">
                <td class='unitBox'>
                  <?php $unit[0] == 'fixed' ? $unitSelect = 'unit-col-3' : $unitSelect = 'unit-col-7';?>
                  <?php $unit[0] == 'fixed' ? $unitInput = 'unit-col-4' : $unitInput = 'hidden';?>
                  <?php echo html::select('unit[]', $versionForm, $unit[0], 'class="form-control ' . $unitSelect . '" onchange="changeOption(this)"')?>
                  <?php echo html::input('unit[]', $unit[1], 'autocomplete="on" class="form-control ' . $unitInput . ' border-left"')?>
                  <?php echo html::select('unit[]', $joint, $unit[2], 'class="form-control unit-col-3 border-left" onchange="change()"');?>
                  <?php echo html::input('unit[]', $unit[3], 'autocomplete="on" class="form-control hidden"')?>
                </td>
                <td class="c-actions">
                  <a href="javascript:void(0)" onclick="addOptions(this)" class="btn btn-link"><i class="icon-plus"></i></a>
                  <a href="javascript:void(0)" onclick="delOptions(this)" class="btn btn-link"><i class="icon-close"></i></a>
                </td>
              </tr>
            <?php endforeach;?>
            <?php else:?>
              <tr class="text-center">
               <td class='unitBox'>
                 <?php echo html::select('unit[]', $versionForm, 0, 'class="form-control unit-col-7" onchange="changeOption(this)"')?>
                 <?php echo html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"')?>
                 <?php echo html::select('unit[]', $joint, 1, 'class="form-control unit-col-3 border-left" onchange="change()"');?>
                 <?php echo html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"')?>
               </td>
               <td class="c-actions">
                <a href="javascript:void(0)" onclick="addOptions(this)" class="btn btn-link"><i class="icon-plus"></i></a>
                <a href="javascript:void(0)" onclick="delOptions(this)" class="btn btn-link"><i class="icon-close"></i></a>
               </td>
              </tr>
          <?php endif;?>
            <tr>
              <td colspan="2">
                <span class="padding"><?php echo $lang->reviewsetting->padding;?> </span>
                  <?php echo html::radio('padding', $lang->reviewsetting->confirm, $result->padding);?>
              </td>
            </tr>
          <?php else:?>
            <tr class="text-center">
              <td class='unitBox'>
                <?php echo html::select('unit[]', $versionForm, 0, 'class="form-control unit-col-7" onchange="changeOption(this)"')?>
                <?php echo html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"')?>
                <?php echo html::select('unit[]', $joint, 1, 'class="form-control unit-col-3 border-left" onchange="change()"');?>
                <?php echo html::input('unit[]', '', 'autocomplete="on" class="form-control hidden"')?>
              </td>
              <td class="c-actions">
                <a href="javascript:void(0)" onclick="addOptions(this)" class="btn btn-link"><i class="icon-plus"></i></a>
                <a href="javascript:void(0)" onclick="delOptions(this)" class="btn btn-link"><i class="icon-close"></i></a>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <span class="padding"><?php echo $lang->reviewsetting->padding;?> </span>
                <?php echo html::radio('padding', $lang->reviewsetting->confirm, '1');?>
              </td>
            </tr>
          <?php endif;?>
          <tr>
            <td>
              <span><?php echo $lang->reviewsetting->versionCase;?></span>
              <span class="versionCase"></span>
            </td>
          </tr>
          <tr>
            <td colspan="2" class="text-center form-actions">
              <?php echo html::hidden('object', $object);?>
              <?php echo html::submitButton($lang->save, "btn btn-wide btn-primary");?>
              <?php echo html::backButton();?>
            </td>
          </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
