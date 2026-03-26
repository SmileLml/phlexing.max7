<?php include $app->getModuleRoot() . 'transfer/view/header.html.php';?>
<?php js::set('productID', $productID);?>
<?php js::set('branchModules', $branchModules);?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->transfer->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post' id='portform'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'> <?php echo $lang->transfer->id?></th>
          <?php foreach($fields as $key => $value):?>
          <?php if($key == 'stepDesc' or $key == 'stepExpect'):?>
          <?php if($key == 'stepExpect') continue;?>
          <th class='c-step'>
            <table class='w-p100 table-borderless'>
              <tr>
                <th><?php echo $fields['stepDesc']['title']?></th>
                <th><?php echo $fields['stepExpect']['title']?></th>
              </tr>
            </table>
          </th>
          <?php elseif($value['control'] != 'hidden'):?>
          <th class='c-<?php echo $key?>'  id='<?php echo $key;?>'>  <?php echo $value['title'];?></th>
          <?php endif;?>
          <?php endforeach;?>
          <?php
          if(!empty($appendFields))
          {
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;

                  $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                  $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                  echo "<th class='$required' style='width: $width'>$field->name</th>";
              }
          }
          ?>
        </tr>
      </thead>
      <tbody>
      </tbody>
      <tfoot class='hidden'>
        <?php include $app->getModuleRoot() . 'transfer/view/tfoot.html.php';?>
      </tfoot>
    </table>
    <?php if(!$this->session->insert) include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php include $app->getModuleRoot() . 'transfer/view/footer.html.php';?>
<script>
$('#showData').on('change', '.picker-select', function(e)
{
    var id        = $(this).attr('id');
    var field     = $(this).attr('data-field');
    var moduleID  = $(this).val();
    var index     = Number(id.replace(/[^\d]/g, " "));
    var stroyValue = $('#story' + index).val() == '' ? 0 : $('#story' + index).val();

    if(field === 'module')
    {
        var storyLink    = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=0&moduleID=' + moduleID + '&storyID=' + stroyValue + '&onlyOption=false&status=&limit=0&type=full&hasParent=1&executionID=0&number=' + index);
        $.get(storyLink, function(stories)
        {
            $('#story' + index).next('.picker').remove();
            $('#story' + index).replaceWith(stories);
            $('#story' + index).picker({chosenMode: true});
            $('#story' + index).attr('isInit', true);
            $('#story' + index).attr('name', 'story[' + index + ']');
        })
    }
});

$('#showData').on('mouseover', '.picker', function(e)
{
    var myPicker = $(this);
    var field    = myPicker.prev().attr('data-field');
    if(typeof(field) == 'undefined') return;
    var name     = myPicker.prev().attr('name');
    var index    = Number(name.replace(/[^\d]/g, " "));

    if(field == 'story') $('#module' + index).change();
});

$('#showData').on('change', '[id^=branch]', function(e)
{
    var index = $(this).attr('id').replace(/[^0-9]/ig, '');
    setImportModules(this.value, productID, index, $('#module' + index).val());
});

function getTbodyLoaded()
{
    $('select[id^=branch]').trigger('change');
}

/**
 * Set modules.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @param  int     $val
 * @access public
 * @return void
 */
function setImportModules(branchID, productID, num, val)
{
        modules = branchModules[branchID];
        if(!modules) modules = '<select id="module' + num + '" name="module[' + num + ']" class="form-control"></select>';
        var dataHtml = $(modules).attr('id', 'module' + num).attr('name', 'module[' + num + ']').prop('outerHTML');

        $('#module' + num).replaceWith(dataHtml);
        $('#module' + num).val(val);
        $("#module" + num + "_chosen").remove();
        $("#module" + num).next('.picker').remove();
        $('#module' + num).picker({chosenMode: true});
        $('#module' + num).addClass('picker-select').attr('data-field', 'module').attr('data-index', num).attr('isInit', true);
        if(val != $('#module' + num).val()) $('#module' + num).trigger('change');
}
</script>
