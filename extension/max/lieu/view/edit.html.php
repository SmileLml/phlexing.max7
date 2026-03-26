<?php
/**
 * The edit view file of lieu module of Ranzhi.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     lieu
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php include '../../common/view/header.modal.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/chosen.html.php';?>
<?php js::set('signIn', $config->attend->signInLimit)?>
<?php js::set('signOut', $config->attend->signOutLimit)?>
<?php js::set('workingHours', $config->attend->workingHours)?>
<form id='ajaxForm' method='post' action="<?php echo $this->createLink('lieu', 'edit', "id=$lieu->id")?>">
  <table class='table table-form table-condensed'>
    <tr>
      <th class='w-60px'><?php echo $lang->lieu->begin?></th>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->lieu->date;?></span>
          <?php echo html::input('begin', $lieu->begin, "class='form-control form-date date-picker-down'")?>
          <span class='input-group-addon fix-border'><?php echo $lang->lieu->time;?></span>
          <?php echo html::input('start', $lieu->start, "class='form-control form-time date-picker-down'")?>
        </div>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->lieu->end?></th>
      <td>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->lieu->date;?></span>
          <?php echo html::input('end', $lieu->end, "class='form-control form-date date-picker-down'")?>
          <span class='input-group-addon fix-border'><?php echo $lang->lieu->time;?></span>
          <?php echo html::input('finish', $lieu->finish, "class='form-control form-time date-picker-down'")?>
        </div>
      </td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->lieu->hours;?></th>
      <td><?php echo html::input('hours', $lieu->hours, "class='form-control'")?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->lieu->overtime;?></th>
      <td><?php echo html::select('overtime[]', $overtimes, $lieu->overtime, "class='form-control chosen' multiple")?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->lieu->trip;?></th>
      <td><?php echo html::select('trip[]', $trips, $lieu->trip, "class='form-control chosen' multiple")?></td>
      <td></td>
    </tr>
    <tr>
      <th><?php echo $lang->lieu->desc;?></th>
      <td><?php echo html::textarea('desc', $lieu->desc, "class='form-control'")?></td>
      <td></td>
    </tr>
    <tr>
      <th></th>
      <td><?php echo baseHTML::submitButton();?></td>
      <td></td>
    </tr>
  </table>
</form>
<script>
$(document).ready(function()
{
    $('#begin, #start, #end, #finish').change(function()
    {
        var begin  = $('#begin').val();
        var end    = $('#end').val();
        var start  = $('#start').val();
        var finish = $('#finish').val();
        if(!begin || !end || !start || !finish) return false;

        begin = begin.replace(/-/g, '/');
        end   = end.replace(/-/g, '/');

        var hours = 0;
        var beginTime = Date.parse(new Date(begin + ' ' + start));
        var endTime   = Date.parse(new Date(end + ' ' + finish));
        if(beginTime > endTime) return false;

        if(begin == end)
        {
            hours = Math.round((endTime - beginTime)/(3600*1000)*100)/100;
            if(hours > workingHours) hours = parseFloat(workingHours);
        }
        else
        {
            var signOutTime  = Date.parse(new Date(begin + ' ' + signOut));
            var signInTime   = Date.parse(new Date(end + ' ' + signIn));
            var hoursStart   = 0;
            var hoursEnd     = 0;
            var hoursContent = 0;
            if(beginTime < signOutTime) hoursStart = Math.round((signOutTime - beginTime)/(3600*1000)*100)/100;
            if(endTime   > signInTime)  hoursEnd   = Math.round((endTime - signInTime)/(3600*1000)*100)/100;
            if(workingHours && hoursStart > workingHours) hoursStart = parseFloat(workingHours);
            if(workingHours && hoursEnd   > workingHours) hoursEnd   = parseFloat(workingHours);
            var days = Math.floor((Date.parse(new Date(end)) - Date.parse(new Date(begin)))/(24*3600*1000));
            if(days > 1) hoursContent = (days - 1) * workingHours;

            hours = hoursStart + hoursEnd + hoursContent;
        }
        $('#hours').val(hours);
    });
})
</script>
<?php include '../../common/view/footer.modal.html.php';?>
