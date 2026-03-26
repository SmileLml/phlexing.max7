<?php
/**
 * The detail view file of attend module of RanZhi.
 *
 * @copyright   Copyright 2009-2018 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     attend
 * @version     $Id$
 * @link        http://www.ranzhi.org
 */
?>
<?php
if(!empty($reviewStatus))
{
    $oldDir = getcwd();
    chdir('../../view/');
    include('./review.html.php');
    chdir($oldDir);
    return;
}
?>
<?php include '../../../common/view/header.modal.html.php';?>
<table class='table table-bordered attendTable'>
  <tr>
    <th><?php echo $lang->attend->account;?></th>
    <td><?php echo zget($users, $attend->account);?></td>
    <th><?php echo $lang->user->dept;?></th>
    <td><?php echo zget($deptList, $user->dept, '');?></td>
  </tr>
  <tr>
    <th><?php echo $lang->attend->date;?></th>
    <td><?php echo formatTime($attend->date, DT_DATE1);?></td>
    <th><?php echo $lang->attend->status;?></th>
    <td><?php echo zget($lang->attend->statusList, $attend->status);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->attend->manualIn;?></th>
    <td><?php echo substr($attend->manualIn, 0, 5);?></td>
    <th><?php echo $lang->attend->manualOut;?></th>
    <td><?php echo substr($attend->manualOut, 0, 5);?></td>
  </tr>
  <tr>
    <th><?php echo $lang->attend->desc;?></th>
    <td colspan='3'><?php echo $attend->desc;?></td>
  </tr>
  <tr>
    <th><?php echo $lang->attend->reason;?></th>
    <td><?php echo zget($lang->attend->reasonList, $attend->reason);?></th>
    <th><?php echo $lang->attend->reviewStatus;?></th>
    <td><?php echo zget($lang->attend->reviewStatusList, $attend->reviewStatus);?></th>
  </tr>
</table>
<?php if($attend->reviewStatus == 'wait'):?>
<div class='page-actions'>
  <?php
  extCommonModel::printLink('attend', 'review', "id={$attend->id}&status=pass",   $lang->attend->reviewStatusList['pass'],   "class='btn reviewPass'");
  extCommonModel::printLink('attend', 'review', "id={$attend->id}&status=reject", $lang->attend->reviewStatusList['reject'], "class='btn loadInModal'");
  ?>
</div>
<?php endif;?>
<script>
$(function()
{
    $('.page-actions a.loadInModal').each(function()
    {
        var href = $(this).attr('href');
        if(href.indexOf('onlybody=yes') < 0) href = href + (href.indexOf('?') > 0 ? '&' : '?') + 'onlybody=yes';
        $(this).removeAttr('href').attr('data-rel', href);
    });

    $(document).off('click', '.reviewPass');
    href = $('a.reviewPass').attr('href');
    $('a.reviewPass').attr('href', '###').attr('data-href', href);
    $(document).on('click', '.reviewPass', function()
    {
        if(confirm(confirmReview.pass))
        {
            var selecter = $(this);

            $.getJSON(selecter.attr('data-href'), function(data)
            {
                if(data.result == 'success')
                {
                    if(data.locate) return location.href = data.locate;
                    return location.reload();
                }
                else
                {
                    alert(data.message);
                    return location.reload();
                }
            });
        }
        return false;
    });
});
</script>
<?php include '../../../common/view/footer.modal.html.php';?>
