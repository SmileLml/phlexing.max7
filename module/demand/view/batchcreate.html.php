<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php js::set('showFields', $showFields);?>
<?php js::set('requiredFields', $config->demand->create->requiredFields);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2>
      <?php echo $demandID ? $demand->title . ' - ' . $this->lang->demand->subdivide : $lang->demand->batchCreate;?>
    </h2>
    <div class="pull-right btn-toolbar">
      <?php if(common::hasPriv('file', 'uploadImages')) echo html::a($this->createLink('file', 'uploadImages', 'module=demand&params=' . helper::safe64Encode("poolID=$poolID&demandID=$demandID")), $lang->uploadImages, '', "data-toggle='modal' data-type='iframe' class='btn btn-primary' data-width='70%'")?>
      <button type='button' data-toggle='modal' data-target="#importLinesModal" class="btn btn-primary"><?php echo $lang->pasteText;?></button>
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=demand&section=custom&key=batchCreateFields')?>
      <?php include $app->getModuleRoot() . 'common/view/customfield.html.php';?>
      <?php if(isonlybody()):?>
      <div class="divider"></div>
      <button id="closeModal" type="button" class="btn btn-link" data-dismiss="modal"><i class="icon icon-close"></i></button>
      <?php endif;?>
    </div>
  </div>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  foreach(explode(',', $config->demand->create->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->demand->list->customBatchCreateFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  unset($visibleFields['module']);
  ?>
  <form class='load-indicator main-form form-ajax' method='post' id='batchCreateForm'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr class='text-center'>
            <th class='c-name required'><?php echo $lang->demand->title;?></th>
            <th class='c-spec'><?php echo $lang->demand->spec;?></th>
            <th class='c-source sourceBox <?php echo zget($visibleFields, 'source', 'hidden')?>'><?php echo $lang->demand->source;?></th>
            <th class='c-verify verifyBox <?php echo zget($visibleFields, 'verify', 'hidden')?>'><?php echo $lang->demand->verify;?></th>
            <th class='c-category'><?php echo $lang->demand->category;?></th>
            <th class='c-pri'><?php echo $lang->demand->pri;?></th>
            <th class='c-assignedTo'><?php echo $lang->demand->assignedTo;?></th>
            <th class='c-product productBox <?php echo zget($visibleFields, 'product', 'hidden')?>'><?php echo $lang->demand->product;?></th>
            <th class='c-duration durationBox <?php echo zget($visibleFields, 'duration', 'hidden')?>'><?php echo $lang->demand->duration;?></th>
            <th class='c-bsa BSABox <?php echo zget($visibleFields, 'BSA', 'hidden')?>'><?php echo $lang->demand->BSA;?></th>
            <th class='c-keywords keywordsBox <?php echo zget($visibleFields, 'keywords', 'hidden')?>'><?php echo $lang->demand->keywords;?></th>
            <th class='c-actions text-left'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <?php
        $users['ditto'] = $lang->story->ditto;
        $lang->demand->priList['ditto']      = $lang->story->ditto;
        $lang->demand->basList['ditto']      = $lang->story->ditto;
        $lang->demand->durationList['ditto'] = $lang->story->ditto;
        $lang->demand->bsaList['ditto']      = $lang->story->ditto;
        $lang->demand->sourceList['ditto']   = $lang->story->ditto;
        ?>
        <tbody>
          <tr class='template'>
            <td style='overflow:visible'>
              <div class="input-group">
                <div class="input-control has-icon-right">
                  <?php echo html::input('title[$id]', '', 'class="form-control title-import input-demand-title"');?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color$id" name="color[$id]" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title$id">
                  </div>
                </div>
              </div>
            </td>
            <td><?php echo html::textarea('spec[$id]', '', 'class="form-control autosize" rows=1');?></td>
            <td class='<?php echo zget($visibleFields, 'source', 'hidden')?> sourceBox'><?php echo html::select('source[$id]', $lang->demand->sourceList, '', 'class="form-control chosen"');?></td>
            <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?> verifyBox'><?php echo html::textarea('verify[$id]', '', 'class="form-control autosize" rows=1');?></td>
            <td><?php echo html::select('category[$id]', $lang->demand->categoryList, '', 'class="form-control chosen"');?></td>
            <td><?php echo html::select('pri[$id]', $lang->demand->priList, '', 'class="form-control chosen"');?></td>
            <td><?php echo html::select('assignedTo[$id]', $assignToList, 'ditto', 'class="form-control chosen"');?></td>
            <td class='<?php echo zget($visibleFields, 'product', 'hidden')?> productBox'>
              <div class='input-group'>
                <?php echo html::select('products[$id][]', $products, '', 'class="form-control chosen" multiple');?>
                <?php echo html::input('undeterminedProduct[$id]', '', "class='form-control hidden' disabled");?>
                <span class='input-group-addon'>
                  <div class="checkbox-primary">
                    <input type="checkbox" name="undetermined[$id]" value="0" id="undetermined$id" />
                    <label for="undetermined0"><?php echo $lang->demand->undetermined;?></label>
                  </div>
                </span>
              </div>
            </td>
            <td class='<?php echo zget($visibleFields, 'duration', 'hidden')?> durationBox'><?php echo html::select('duration[$id]', $lang->demand->durationList, '', 'class="form-control chosen"');?></td>
            <td class='<?php echo zget($visibleFields, 'BSA', 'hidden')?> BSABox'><?php echo html::select('BSA[$id]', $lang->demand->bsaList,  '', 'class="form-control chosen"');?></td>
            <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input('keywords[$id]', '', 'class="form-control"');?></td>
            <td class='c-actions text-left'>
              <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="<?php echo count($visibleFields) + 6;?>" class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
</div>
<div>
  <?php $i = '%i%';?>
  <table class='hidden'>
    <tr id='addRow' class='hidden'>
      <td style='overflow:visible'>
        <div class="input-group">
          <div class="input-control has-icon-right">
            <?php echo html::input("title[$i]", '', 'class="form-control title-import input-demand-title"');?>
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <input type="hidden" class="colorpicker" id="color<?php echo $i;?>" name="color[<?php echo $i;?>]" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title<?php echo $i;?>">
            </div>
          </div>
        </div>
      </td>
      <td><?php echo html::textarea("spec[$i]", '', 'class="form-control autosize" rows=1');?></td>
      <td class='<?php echo zget($visibleFields, 'source', 'hidden')?> sourceBox'><?php echo html::select("source[$i]", $lang->demand->sourceList, 'ditto', 'class="form-control chosen"');?></td>
      <td class='<?php echo zget($visibleFields, 'verify', 'hidden')?> verifyBox'><?php echo html::textarea("verify[$i]", '', 'class="form-control autosize" rows=1');?></td>
      <td><?php echo html::select("category[$i]", $lang->demand->categoryList, '', 'class="form-control chosen"');?></td>
      <td><?php echo html::select("pri[$i]", $lang->demand->priList, 'ditto', 'class="form-control chosen"');?></td>
      <td><?php echo html::select("assignedTo[$i]", $users, '', 'class="form-control chosen"');?></td>
      <td class='<?php echo zget($visibleFields, 'product', 'hidden')?> productBox'>
        <div class='input-group'>
          <?php echo html::select("products[$i][]", $products, '', 'class="form-control chosen" multiple');?>
          <?php echo html::input("undeterminedProduct[$i]", '', "class='form-control hidden' disabled");?>
          <span class='input-group-addon'>
            <div class="checkbox-primary">
              <input type="checkbox" name="undetermined[<?php echo $i;?>]" value="0" id="undetermined<?php echo $i;?>" />
              <label for="undetermined0"><?php echo $lang->demand->undetermined;?></label>
            </div>
          </span>
        </div>
      </td>
      <td class='<?php echo zget($visibleFields, 'duration', 'hidden')?> durationBox'><?php echo html::select("duration[$i]", $lang->demand->durationList, 'ditto', 'class="form-control chosen"');?></td>
      <td class='<?php echo zget($visibleFields, 'BSA', 'hidden')?> BSABox'><?php echo html::select("BSA[$i]", $lang->demand->bsaList, 'ditto', 'class="form-control chosen"');?></td>
      <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?> keywordsBox'><?php echo html::input("keywords[$i]", '', 'class="form-control"');?></td>
      <td class='c-actions text-left'>
        <a href='javascript:;' onclick='addRow(this)' class='btn btn-link'><i class='icon-plus'></i></a>
        <a href='javascript:;' onclick='deleteRow(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
      </td>
    </tr>
  </table>
</div>
<script>
var imageTitles  = <?php echo empty($titles) ? '""' : json_encode($titles);?>;
var demandTitles = <?php echo empty($titles) ? '""' : json_encode(array_keys($titles));?>;

$('#batchCreateForm').batchActionForm(
{
    idStart: 1,
    idEnd: <?php echo max((empty($titles) ? 1 : count($titles)), 10)?>,
    datetimepicker: false,
    rowCreator: function($row, index)
    {
        rowIndex = index; // Set the index for the add element operation
        $row.find('select.chosen,select.picker-select').each(function()
        {
            var $select = $(this);
            if($select.hasClass('picker-select')) $select.parent().find('.picker').remove();
            if(index == 1) $select.find("option[value='ditto']").remove();
            if(index > 1 && $select.find('option[value="ditto"]').length > 0) $select.val('ditto');
            $select.chosen();
            setTimeout(function()
            {
                $select.next('.chosen-container').find('.chosen-drop').width($select.closest('td').width() + 50);
            }, 200);
        });

        var demandTitle = demandTitles && demandTitles[index - 1];
        if (demandTitle !== undefined && demandTitle !== null)
        {
            $row.find('.input-demand-title').val(demandTitle).after('<input type="hidden" name="uploadImage[' + index + ']" id="uploadImage[' + index + ']" value="' + imageTitles[demandTitle] + '">');
        }

        if(index == 1) $row.find('td.c-actions > a:last').remove();

        /* Implement a custom form without feeling refresh. */
        var fieldList = ',' + showFields + ',';
        $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
        {
            var field     = ',' + $(this).val() + ',';
            var $field    = $row.find('[name^=' + $(this).val() + ']');
            var required  = ',' + requiredFields + ',';
            var $fieldBox = $row.find('.' + $(this).val() + 'Box' );
            if(fieldList.indexOf(field) >= 0 || required.indexOf(field) >= 0)
            {
                $fieldBox.removeClass('hidden');
                $field.removeAttr('disabled');
            }
            else if(!$fieldBox.hasClass('hidden'))
            {
                $fieldBox.addClass('hidden');
                $field.attr('disabled', true);
            }
        })
    }
});
</script>
<?php include $app->getModuleRoot() . 'common/view/pastetext.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
