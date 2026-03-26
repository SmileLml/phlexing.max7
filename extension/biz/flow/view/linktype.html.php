<div class='modal fade' id='linkTypeBox'>
  <div class='modal-dialog modal-sm'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class='modal-title'><?php echo $lang->workflowaction->default->actions['link'];?></h4>
      </div>
      <div class='modal-body'>
        <table class='table table-form'>
          <tr>
            <th class='w-120px'><?php echo $lang->flow->selectLinkType;?></th>
            <td><?php echo html::select('linkType', array('' => '') + $linkPairs, '', "class='form-control chosen'");?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
