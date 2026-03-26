<?php
/**
 * The view mobile web file of feedback module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen <chenfei@cnezsoft.com>
 * @package     feedback
 * @version     $Id: view.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/m.header.html.php';?>
<div id='page' class='list-with-actions'>
  <div class='heading gray'>
    <div class='title'>
      <span class='prefix'><strong><?php echo $feedback->id;?></strong></span>
      <strong>
      <?php
      if($feedback->public) echo "<span class='label label-info'>{$lang->feedback->public}</span>";
      echo $feedback->title;
      ?>
      </strong>
    </div>
    <nav class='nav'><a href="javascript:history.go(-1);" class='btn primary'><?php echo $lang->goback;?></a></nav>
  </div>
  <div class='box'>
    <table class='table bordered table-detail'>
      <tr>
        <td class='w-80px'><?php echo $lang->feedback->product;?></td>
        <td><?php echo $product;?></td>
      </tr>
      <tr>
        <td><?php echo $lang->feedback->status;?></td>
        <td><span class='label status-<?php echo $feedback->status;?>'><?php echo $this->processStatus('feedback', $feedback);?></span></td>
      </tr>
      <tr>
        <td><?php echo $lang->feedback->desc;?></td>
        <td><?php echo $feedback->desc;?></td>
      </tr>
    </table>
  </div>

  <?php if(!empty($feedback->files)):?>
  <div class='heading gray'>
    <div class='title'><i class='icon icon-file-text-o'> </i><?php echo $lang->file->common;?></div>
  </div>
  <div class='box'>
    <div class='list'>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $feedback->files, 'fieldset' => 'false'))?>
    </div>
  </div>
  <?php endif;?>

  <?php if($feedback->result and $type):?>
  <div class='heading gray'>
    <div class='title'><i class='icon icon-file-text-o'> </i><?php echo $lang->feedback->$type;?></div>
  </div>
  <div class='box'>
    <span class="prefix"> <strong>#<?php echo $feedback->resultInfo->id;?></strong> </span>
    <span> <?php echo common::hasPriv($type, 'view') ? html::a($this->createLink($type, 'view', "id={$feedback->resultInfo->id}"), $feedback->resultInfo->title) : $feedback->resultInfo->title;?> </span>
    <span class='<?php echo 'pri' . zget($lang->$type->priList, $feedback->resultInfo->pri);?>'> <?php echo zget($lang->$type->priList, $feedback->resultInfo->pri)?> </span>
    <span class="label label-info"><?php echo $this->processStatus($type, $feedback->resultInfo);?></span>
  </div>
  <?php endif;?>

  <div class='section' id='history'>
    <?php include $app->getModuleRoot() . 'common/view/m.action.html.php';?>
  </div>

  <nav id='actionNav' class='nav affix dock-bottom nav-auto footer-actions'>
  <?php
  $params = "feedbackID=$feedback->id";

  if($app->user->account == $feedback->openedBy and common::hasPriv('feedback', 'comment')) echo html::a($this->createLink('feedback', 'comment', "feedbackID=$feedback->id&type=asked"), $lang->feedback->ask,'', "data-display='modal' data-placement='bottom'");
  if(empty($app->user->feedback) and strpos('closed|clarify|noreview', $feedback->status) === false)
  {
      common::printIcon('feedback', 'comment', "feedbackID=$feedback->id&type=replied", $feedback, 'button', '', '', '', false, "data-display='modal' data-placement='bottom'", $lang->feedback->reply);
      $this->app->loadLang('story');
      $this->app->loadLang('bug');
      $this->app->loadLang('task');
      $this->app->loadLang('todo');
      $link = $this->createLink('story', 'create', "product=$feedback->product&branch=0&moduleID=0&storyID=0&executionID=0&bugID=0&planID=0&todoID=0&extra=$params");
      if(common::hasPriv('story', 'create') and $config->global->flow == 'full' or $config->global->flow == 'onlyStory') echo html::a($link, $lang->story->common, '', "data-display='modal' data-placement='bottom'");
      $link = $this->createLink('task', 'create', "executionID=0&storyID=0&moduleID=0&taskID=0&extra=$params");
      if(common::hasPriv('task', 'create') and $config->global->flow == 'full' or $config->global->flow == 'onlyTask') echo html::a($link, $lang->task->common, '', "data-display='modal' data-placement='bottom'");
      $link = $this->createLink('bug', 'create', "product=$feedback->product&branch=0&extra=$params");
      if(common::hasPriv('bug', 'create') and $config->global->flow == 'full' or $config->global->flow == 'onlyTest') echo html::a($link, $lang->bug->common, '', "data-display='modal' data-placement='bottom'");
      $link = $this->createLink('todo', 'create', "date=today&userID=&from=feedback&feedbackID=$feedbackID");
      if(common::hasPriv('todo', 'create') and $config->global->flow == 'full') echo html::a($link, $lang->todo->common, '', "data-display='modal' data-placement='bottom'");
  }
  common::printIcon('feedback', 'close', "feedbackID=$feedback->id", $feedback, 'button', '', '', '', false, "data-display='modal' data-placement='bottom'", $lang->feedback->close);
  if($feedback->public)
  {
      if(common::hasPriv('feedback', 'comment')) echo html::a(inlink('comment', "feedbackID=$feedback->id&type=commented"), $lang->feedback->comment, '', "data-display='modal' data-placement='bottom'");
      echo html::a("javascript:like($feedback->id)", "<i class='icon icon-thumbs-up'></i> ({$feedback->likesCount})", '', "id='likeLink'");
  }
  common::printIcon('feedback', 'edit', $params, $feedback, 'button', '', '', '', false, "data-display='modal' data-placement='bottom'", $lang->edit);
  if($this->feedback->isClickable($feedback, 'delete')) echo html::a(inlink('delete', $params), $lang->delete, 'hiddenwin');
  ?>
  </nav>
</div>

<script>
function like(feedbackID)
{
    var likeLink = createLink('feedback', 'ajaxLike', 'feedbackID=' + feedbackID);
    $.get(likeLink, function(likeHtml)
    {
        $('#likeLink').replaceWith(likeHtml);
    });
}
</script>
<?php include $app->getModuleRoot() . 'common/view/m.footer.html.php';?>
