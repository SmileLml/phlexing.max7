<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@cnezsoft.com>
 * @package     assetlib
 * @version     $Id: editstory.html.php 4645 2013-04-11 08:32:09Z
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div class='main-content' id='mainContent'>
  <div class="center-block">
    <div class='main-header'>
      <h2><?php echo $lang->assetlib->editStory;?></h2>
    </div>
    <form method='post' class="main-form form-ajax" enctype='multipart/form-data' id='storyform'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th><?php echo $lang->story->name;?></th>
            <td><?php echo html::input('title', $story->title, "class='form-control' required");?></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->pri;?></th>
            <td><?php echo html::select('pri', $lang->story->priList, $story->pri, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->estimate;?></th>
            <td><?php echo html::input('estimate', $story->estimate, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->keywords;?></th>
            <td><?php echo html::input('keywords', $story->keywords, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->spec;?></th>
            <td colspan="2"><?php echo html::textarea('spec', $story->spec, 'row="6"');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->story->verify;?></th>
            <td colspan="2"><?php echo html::textarea('verify', $story->verify, 'row="6"');?></td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . html::backButton();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
