<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div class="main-row fade" id="mainRow">
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-book text-muted"></i>
          <?php echo $node->title . " <i class='icon-angle-right'></i> " . ($node->type == 'article' ? $lang->baseline->editArticle : $lang->baseline->editChapter) ;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
          </div>
        </nav>
      </div>
      <div class='panel-body'>
        <?php if($node->contentType == 'html')     include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
        <?php if($node->contentType == 'markdown') include $app->getModuleRoot() . 'common/view/markdown.html.php';?>
<form class='load-indicator main-form form-ajax' method='post' action=<?php echo inlink('edittemplate', "templateID=$node->id")?> enctype='multipart/form-data' id='dataform'>
          <table class='table table-form'>
            <?php if($node->type != 'systemchapter'):?>
            <tr>
              <th><?php echo $lang->baseline->chapter;?></th>
              <td>
                <?php echo html::hidden('lib', $node->lib)?>
                <span><?php echo html::select('parent', $optionMenu, $node->parent, "class='form-control chosen'");?></span>
              </td><td></td>
            </tr>
            <?php endif;?>
            <?php if($node->chapterType == 'system'):?>
            <tr>
              <th><?php echo $lang->baseline->chapterType;?></th>
              <td><?php echo html::select("chapterType", $lang->baseline->chapterTypeList, $node->chapterType, "class='form-control chosen'");?></td>
              <td></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $node->type == 'article' ? $lang->book->title : $lang->baseline->chapterName;?></th>
              <td colspan='2'><?php echo html::input('title', $node->title, "class='form-control' autocomplete='off' required");?></td>
            </tr>
            <tr>
              <th><?php echo $lang->book->keywords;?></th>
              <td colspan='2'><?php echo html::input('keywords', $node->keywords, "class='form-control' autocomplete='off'");?></td>
            </tr>
            <?php if($node->type == 'article'):?>
            <tr id='contentBox' <?php if($node->type == 'url') echo "class='hidden'"?>>
              <th><?php echo $lang->baseline->docContent;?></th>
              <td colspan='2'><?php echo html::textarea('content', $node->type == 'url' ? '' : htmlspecialchars($node->content), "style='width:100%; height:200px'");?></td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='3' class='text-center form-actions'>
                <?php
                echo html::hidden('editedDate', $node->editedDate);
                echo html::hidden('contentType', $node->contentType);
                echo html::hidden('acl', 'open');
                echo html::submitButton();
                echo html::backButton();
                ?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
