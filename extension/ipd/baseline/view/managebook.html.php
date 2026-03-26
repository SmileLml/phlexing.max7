<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class="main-row fade" id="mainRow">
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-book text-muted"></i>
          <?php echo $template->title . " <i class='icon-angle-right'></i> " . ($node ? $node->title : $lang->baseline->manageBook);?>
        </div>
      </div>
      <div class='panel-body'>
        <form class='load-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
          <table class='table table-form'>
            <thead>
              <tr class='text-center'>
                <th class='w-p10'><?php echo $lang->book->type;?></th>
                <th class='w-p10'><?php echo $lang->baseline->chapterType;?></th>
                <th><?php echo $lang->book->title;?></th>
                <th class='w-p30'><?php echo $lang->book->keywords;?></th>
                <th class='w-80px'><?php echo $lang->actions; ?></th>
              </tr>
            </thead>

            <tbody>
              <?php $maxID = 0;?>
              <?php foreach($children as $child):?>
              <?php $maxID = $maxID < $child->id ? $child->id : $maxID;?>
              <tr class='text-center text-middle'>
                <td><?php echo html::select("type[$child->id]",     $lang->book->typeList, $child->type, "class='form-control'");?></td>
                <td><?php echo html::select("chapterType[$child->id]", $lang->baseline->chapterTypeList, $child->chapterType, "class='form-control'");?></td>
                <td><?php echo html::input("title[$child->id]",     $child->title,     "class='form-control'");?></td>
                <td><?php echo html::input("keywords[$child->id]",  $child->keywords,  "class='form-control'");?></td>
                <td>
                  <?php echo html::hidden("order[$child->id]", $child->order, "class='order'");?>
                  <?php echo html::hidden("mode[$child->id]", 'update');?>
                  <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
                </td>
              </tr>
              <?php endforeach;?>

              <?php for($i = 0; $i < 5; $i ++):?>
              <tr class='text-center text-middle node'>
                <td><?php echo html::select("type[]", $lang->book->typeList, '', "class='form-control'");?></td>
                <td><?php echo html::select("chapterType[]", $lang->baseline->chapterTypeList, '', "class='form-control'");?></td>
                <td><?php echo html::input("title[]", '', "class='form-control'");?></td>
                <td><?php echo html::input("keywords[]", '', "class='form-control'");?></td>
                <td>
                  <?php echo html::hidden("order[]", '', "class='order'");?>
                  <?php echo html::hidden("mode[]", 'new');?>
                  <i class='icon-arrow-up'></i> <i class='icon-arrow-down'></i>
                </td>
              </tr>
              <?php endfor;?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan='4' class='text-center form-actions'>
                  <?php echo html::submitButton() . html::backButton();?>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('maxID', $maxID)?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
