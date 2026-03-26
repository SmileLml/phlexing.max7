<?php
/**
 * The editdoc view of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: editdoc.html.php 975 2021-06-30 13:25:25Z jajacn@126.com $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php if($doc->contentType == 'html' || $doc->contentType == 'doc')     include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php if($doc->contentType == 'markdown') include $app->getModuleRoot() . 'common/view/markdown.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $doc->id;?></span>
        <?php $viewMethod = $objectType == 'practice' ? 'practiceView' : 'componentView';?>
        <?php echo html::a($this->createLink('assetlib', $viewMethod, "docID=$doc->id"), $doc->title, '', "title='$doc->title'");?>
        <small> <?php echo $lang->arrow . ' ' . $lang->doc->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->doc->title;?></th>
          <td colspan='2'><?php echo html::input('title', $doc->title, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->doc->keywords;?></th>
          <td colspan='2'><?php echo html::input('keywords', $doc->keywords, "class='form-control'");?></td>
        </tr>
        <tr id='contentBox'>
          <th><?php echo $lang->doc->content;?></th>
          <td colspan='2'><?php echo html::textarea('content', $doc->type == 'url' ? '' : htmlspecialchars($doc->content), "style='width:100%; height:200px'") . html::hidden('contentType', $doc->contentType);?></td>
        </tr>
        <tr id='fileBox'>
          <th><?php echo $lang->doc->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildoldform');?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center form-actions'>
            <?php
            echo html::hidden('editedDate', $doc->editedDate);
            echo html::submitButton();
            echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
