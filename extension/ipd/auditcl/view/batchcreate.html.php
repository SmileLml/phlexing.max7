<?php include $app->getModuleRoot() . 'common/view/header.html.php';?> <?php
    $formUrl = $this->createLink('auditcl', 'batchcreate');
    js::set('formUrl', $formUrl);
?>
<style>
#main .container{background:#ffffff;padding-top:20px;}
.required{top:-8px;}
.check-title{width: 70%; float: left; margin-right: 10px; margin-bottom: 5px;}
table td .del-item{border: 0}
.item{height:37px;}
</style>
<form class="load-indicator main-form form-ajax" target="hiddenwin" method="post">
  <?php echo html::hidden('model', $model);?>
  <table class="table table-bordered">
    <thead>
    <tr><th colspan="7"><?php echo $lang->auditcl->batchCreate;?></th></tr>
      <tr>
        <th><?php echo $lang->auditcl->objectID;?><span class="required"></span></th>
        <th><?php echo $lang->auditcl->type;?><span class="required"></span></th>
        <th><?php echo $lang->auditcl->activeDoc;?></th>
        <th><?php echo $lang->auditcl->practiceArea;?><span class="required"></span></th>
        <th><?php echo $lang->auditcl->objectType;?></th>
        <th><?php echo $lang->auditcl->title;?><span class="required"></span></th>
        <th style="width:50px;"><?php echo $lang->auditcl->shortcuts;?></th>
      </tr>
    </thead>
    <tbody id="formlist">
      <?php foreach($option as $id => $object):?>
        <tr>
          <td id="object<?php echo $id;?>" rowspan = <?php echo 3 + count($object['activity']) + count($object['zoutput']);?>><?php echo $object['name'];?></td>
        </tr>
        <td id="activity<?php echo $id;?>" rowspan = <?php echo 1 + count($object['activity']);?>><?php echo zget($lang->auditcl->objectTypeList, 'activity');?></td>
        </tr>
          <?php if($object['activity']):?>
          <?php foreach($object['activity'] as $index => $review):?>
            <?php if(is_object($review)):?>
            <tr>
              <td>
               <?php $title = $option[$review->process][$review->objectType][$review->objectID];?>
               <?php if(is_object($title)) $title = $title->title;?>
               <?php echo $title;?>
              </td>
              <td>
               <?php echo zget($this->lang->auditcl->practiceAreaList, $review->practiceArea);?>
              </td>
              <td>
               <?php echo zget($this->lang->auditcl->typeList, $review->type);?>
              </td>
              <td>
                <div><?php echo $review->title;?></div>
              </td>
              <td></td>
            </tr>
            <?php else:?>
            <tr>
              <?php echo html::hidden('objectID[]', $id);?>
              <?php echo html::hidden('activityDoc[]', $index);?>
              <?php echo html::hidden('objectType[]', 'activity');?>
              <td><?php echo $review;?></td>
              <td>
               <?php echo html::select("practiceArea[$id-$index-activity][]", $this->lang->auditcl->practiceAreaList, '', 'class="form-control"');?>
              </td>
              <td>
               <?php echo html::select("type[$id-$index-activity][]", $this->lang->auditcl->typeList, '', 'class="form-control"');?>
              </td>
              <td>
                <div class="title-<?php echo $id . '-' . $index . '-activity';?> item">
                  <?php echo html::input("title[$id][$index][activity][]",'','class="form-control check-title"');?>
                  <a href="javascript:;" class="btn btn-link" onclick='addTitle("<?php echo $id . '-' . $index . '-activity';?>", this)'><i class="icon icon-plus"></i></a>
                  <a href="javascript:;" class="btn btn-link" onclick='delTitle(this)'><i class="icon icon-minus"></i></a>
                </div>
              </td>
              <td>
                <a href="javascript:;" class="btn del-item" data-id="<?php echo $id?>" data-object="activity">
                <i class="icon-close"></i></a>
              </td>
            </tr>
            <?php endif;?>
          <?php endforeach;?>
          <?php endif;?>
        <tr>
        <td id="zoutput<?php echo $id;?>" rowspan = <?php echo 1 + count($object['zoutput']);?>><?php echo zget($lang->auditcl->objectTypeList, 'zoutput');?></td>
        </tr>
          <?php if($object['zoutput']):?>
          <?php foreach($object['zoutput'] as $index => $review):?>
            <?php if(is_object($review)):?>
            <tr>
              <td>
               <?php $title = $option[$review->process][$review->objectType][$review->objectID];?>
               <?php if(is_object($title)) $title = $title->title;?>
               <?php echo $title;?>
              </td>
              <td>
               <?php echo zget($this->lang->auditcl->practiceAreaList, $review->practiceArea);?>
              </td>
              <td>
               <?php echo zget($this->lang->auditcl->typeList, $review->type);?>
              </td>
              <td>
                <div><?php echo $review->title;?></div>
              </td>
              <td></td>
            </tr>
            <?php else:?>
            <tr>
              <?php echo html::hidden('objectID[]', $id);?>
              <?php echo html::hidden('activityDoc[]', $index);?>
              <?php echo html::hidden('objectType[]', 'zoutput');?>
              <td><?php echo $review;?></td>
              <td>
               <?php echo html::select("practiceArea[$id-$index-zoutput][]", $this->lang->auditcl->practiceAreaList, '', 'class="form-control"');?>
              </td>
              <td>
               <?php echo html::select("type[$id-$index-zoutput][]", $this->lang->auditcl->typeList, '', 'class="form-control"');?>
              </td>
              <td>
                <div class="title-<?php echo $id . '-' . $index . '-zoutput';?>" id="title-<?php echo $id . '-' . $index . '-zoutput';?>">
                  <?php echo html::input("title[$id][$index][zoutput][]",'','class="form-control check-title"');?>
                  <a href="javascript:;" class="btn btn-link" onclick='addTitle("<?php echo $id . '-' . $index . '-zoutput';?>", this)'><i class="icon icon-plus"></i></a>
                  <a href="javascript:;" class="btn btn-link" onclick='delTitle(this)'><i class="icon icon-minus"></i></a>
                </div>
              </td>
              <td>
                <a href="javascript:;" class="btn del-item" data-id="<?php echo $id?>" data-object="zoutput">
                <i class="icon-close"></i></a>
              </td>
            </tr>
            <?php endif;?>
          <?php endforeach;?>
          <?php endif;?>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr class='form-actions text-center'>
        <td colspan='7'>
          <?php echo html::submitButton($lang->save);?>
          <?php echo html::backButton();?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script>
$(".del-item").on('click', function()
{
    $(this).parent().parent().remove();
    var itemID = $(this).attr('data-id');
    var object = $(this).attr('data-object');

    var objectRow = $("#object" + itemID).attr('rowspan');
    var typeRow   = $("#" + object + itemID).attr('rowspan');
    $("#object" + itemID).attr('rowspan', Number(objectRow) - 1);
    $("#" + object + itemID).attr('rowspan', Number(typeRow) - 1);
});

function addTitle(titleID, own)
{
    var titleObj = "title-" + titleID;
    var titleHtml = $(own).closest('.' + titleObj).html();
    $(own).parent().after("<div class='" + titleObj +" item'>" + titleHtml + "</div>");
}

function delTitle(titleID)
{
    if($(titleID).parent().parent().children().length == 1) return false;
    $(titleID).parent().remove();
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
