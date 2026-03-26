<?php
/**
 * The batchCreate mobile view file of effort module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     effort
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div class='heading divider'>
  <div class='title'><strong><?php echo $lang->effort->batchCreate;?></strong></div>
  <nav class='nav'><a data-dismiss='display'><i class='icon icon-remove muted'></i></a></nav>
</div>
<form class='has-padding content' method='post' action='<?php echo $this->createLink('effort', 'batchCreate');?>' target='hiddenwin' id='batchCreateForm' data-form-refresh='#page'>
  <div class='control'>
    <label for='date'><?php echo $lang->effort->date?></label>
    <input type='date' name='date' value='<?php echo date(DT_DATE1)?>' class='input' />
  </div>
  <?php for($i = 0; $i < 1; $i ++):?>
  <?php echo html::hidden("id[]", $i);?>
  <div class="control">
    <label for='objectType'><?php echo $lang->effort->objectType;?></label>
    <div class='select'>
      <?php echo html::select('objectType[]', $typeList, 'custom');?>
    </div>
  </div>
  <div class="control">
    <label for='execution'><?php echo $lang->effort->execution;?></label>
    <div class='select'>
      <?php echo html::select('execution[]', $executions, 0);?>
    </div>
  </div>
  <div class='row'>
    <div class='cell-6'>
      <div class='control'>
        <label for='consumed'><?php echo $lang->effort->consumed?></label>
        <input type='number' name='consumed[]' value='' step='0.01' class='input' />
      </div>
    </div>
    <div class='cell-6'>
      <div class='control'>
        <label for='left'><?php echo $lang->effort->left?></label>
        <input type='number' name='left[<?php echo $i?>]' value='' step='0.01' class='input' />
      </div>
    </div>
  </div>
  <div class='control'>
    <label for='work'><?php echo $lang->effort->work?></label>
    <?php echo html::input('work[]', '', "class='input'");?>
  </div>
  <div class='control'>
    <button type='submit' class='btn primary'><?php echo $lang->save;?></button>
  </div>
  <?php endfor;?>
</form>
<?php js::set('executionTask', $executionTask);?>
<script>
$(function()
{
    $("input[name*='left']").each(function(){
        $(this).closest('div.control').hide();
    });
    $(document).on('change', 'select#objectType', function()
    {
        var value     = $(this).val();
        var executionID = executionTask[value] ? executionTask[value] : 0
        var $execution  = $(this).parent('div').parent('div').next('div.control').find('select#execution');
        $execution.val(executionID);

        var $leftInput = $(this).parent('div').parent('div').next().next('div.row').find("input[name*='left']");
        if(value.indexOf('task_') >= 0)
        {
            $leftInput.closest('div.control').show();
        }
        else
        {
            $leftInput.closest('div.control').hide();
            $execution.val(0);
        }
    });
});
</script>
