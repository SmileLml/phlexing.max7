<?php
/**
 * The side view file of traincourse module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     traincourse
 * @version     $Id: side.html.php 4029 2022-02-10 10:50:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php $coursePairs = $this->traincourse->getPairs();?>
<div class="side-col" style="width: 300px" data-min-width="300">
  <div class="cell" id='course'>
    <ul data-name="docsTree" data-ride="tree" data-initial-state="preserve" class="tree no-margin">
      <?php foreach($coursePairs as $id => $courseName):?>
        <?php
        if($this->app->moduleName == 'traincourse' and $this->app->methodName == 'editChapter')//Finish task: http://vision.5upm.com/task-view-570.html
        {
            $activeClass = '';
            if($id != $courseID) continue;
        }
        else
        {
            $activeClass = $id == $courseID ? 'active' : '';
        }
        ?>
        <li <?php echo "class='$activeClass'";?>>
          <?php echo html::a($this->createLink('traincourse', 'manageChapter', "courseID=$id"), "<i class='icon icon-folder-o'></i> " . $courseName, "class='text-ellipsis' title='{$courseName}'");?>
          <ul>
            <?php $serials  = $this->traincourse->computeSN($id);?>
            <?php $nodeList = $this->traincourse->getCourseStructure($id);?>
            <?php foreach($nodeList as $nodeInfo):?>
            <?php $serial = $nodeInfo->type != 'course' ? $serials[$nodeInfo->id] : '';?>
            <?php if($nodeInfo->parent != 0) continue;?>
            <?php $activeClass = (isset($chapter->id) && $chapter->id == $nodeInfo->id) ? 'active' : '';?>
            <li <?php echo "class='open $activeClass'";?>>
              <?php if($this->methodName == 'editchapter'):?>
              <?php echo "<span class='item'>{$serial} " . html::a(helper::createLink('traincourse', 'editChapter', "chapterID=$nodeInfo->id"), $nodeInfo->name) . '</span>';?>
              <?php elseif($nodeInfo->type == 'chapter'):?>
              <?php echo "<span class='item'>{$serial} {$nodeInfo->name}</span>";?>
              <?php elseif($nodeInfo->type == 'video'):?>
              <?php echo "<span class='item'>{$serial} " . html::a(helper::createLink('traincourse', 'view', "chapterID=$nodeInfo->id"), $nodeInfo->name) . '</span>';?>
              <?php endif;?>

              <?php if(!empty($nodeInfo->children)) $this->traincourse->getFrontCatalog($nodeInfo->children, $serials, isset($chapter->id) ? $chapter->id : 0);?>
            </li>
            <?php endforeach;?>
          </ul>
        </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
