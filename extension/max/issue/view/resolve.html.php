<?php
/**
 * The resolve view of issue module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     issue
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.html.php';
include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
include $app->getModuleRoot() . 'common/view/datepicker.html.php';
include $app->getModuleRoot() . 'common/view/sortable.html.php';
?>
<?php
echo js::set('issueID', $issue->id);
echo js::set('resolveLink', $this->createLink('issue', 'ajaxGetResolveForm', "projectID=$issue->project"));
?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $issue->id;?></span>
        <?php echo "<span title='$issue->title'>" . $issue->title . '</span>';?>
      </div>
    </div>
    <div class="modal-body" style="min-height: 282px; overflow: auto;">
    <form class='form-ajax' method='post'>
      <table class="table table-form" id="solutionTable">
        <tr>
          <th><?php echo $lang->issue->resolution;?></th>
          <td>
            <?php echo html::select('resolution', $lang->issue->resolveMethods, 'resolved', 'class="form-control chosen" onchange="getSolutions()"');?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->resolutionComment;?></th>
          <td colspan='3'><textarea name='resolutionComment' class='form-control' rows='5' id='resolutionComment'><?php echo $issue->resolutionComment;?></textarea></td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->resolvedBy;?></th>
          <td>
            <?php echo html::select('resolvedBy', $users, $this->app->user->account, "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->issue->resolvedDate;?></th>
          <td>
             <div class='input-group has-icon-right'>
               <?php echo html::input('resolvedDate', date('Y-m-d'), "class='form-control form-date'");?>
               <label for="date" class="input-control-icon-right"><i class="icon icon-delay"></i></label>
             </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <div class='form-action'><?php echo html::submitButton();?></div>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script>
function getSolutions()
{
    parent.$('#triggerModal .modal-body').height($('.modal-body').css('min-height'));

    var mode = $("#resolution").val();
    $.ajax(
    {
        url: resolveLink,
        dataType: "html",
        data:{mode: mode, issueID: issueID},
        type: "post",
        success:function(data)
        {
            $("#solutionTable").html(data);
            $("#resolution").chosen();
            $("#resolvedBy").chosen();
            $("#resolvedDate").fixedDate().datepicker();

            if(mode == 'tostory' || mode == 'tobug' || mode == 'totask')
            {
                $("#module").chosen();
                $("#type").chosen();
                $("#assignedTo").chosen();
                $("#reviewer").chosen();
                $("#pri").chosen();
            }

            switch(mode)
            {
                case 'totask':
                    $("#execution").chosen();
                    $("#desc").kindeditor();
                    $("#estStarted").fixedDate().datepicker();
                    $("#deadline").fixedDate().datepicker();
                    break;
                case 'tobug':
                    $("#product").chosen();
                    $("#openedBuild").chosen();
                    $("#steps").kindeditor();
                    $("#deadline").fixedDate().datepicker();
                    $("#story").chosen();
                    break;
                case 'tostory':
                    $("#product").chosen();
                    $("#spec").kindeditor();
                    $("#verify").kindeditor();
                    break;
                case 'torisk':
                    $("#source").chosen();
                    $("#category").chosen();
                    $("#strategy").chosen();
                    break;
                case 'resolved':
                    $("#resolutionComment").kindeditor();
                    break;
            }
        }
    })

}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
