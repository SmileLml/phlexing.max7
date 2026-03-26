<?php
/**
 * The view view of charter module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     charter
 * @version     $Id: view.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<div id='mainContent' class="main-row">
  <div class="col-8 main-col">
    <div class="row">
      <div class="col-sm-12">
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title">
              <span class="label-id"><?php echo $charter->id;?></span>
              <span class="label label-<?php echo $charter->status;?> label-outline"><?php echo zget($lang->charter->statusList, $charter->status);?></span> <?php echo $charter->name;?>
              <?php if($charter->deleted):?>
              <span class='label label-danger'><?php echo $lang->charter->deleted;?></span>
              <?php endif;?>
            </h2>
            <div class="detail-content article-content">
              <p><?php echo $charter->spec;?></p>
            </div>
          </div>
          <div class="detail">
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <tr>
                    <th class="c-category"><?php echo $lang->charter->category;?></th>
                    <td><strong><?php echo zget($lang->charter->categoryList, $charter->category);?></strong></td>
                    <?php $levelClass = $charter->level ? "label-pri label-pri-{$charter->level}" : '';?>
                    <th class="c-level"><?php echo $lang->charter->level;?></th>
                    <td><strong><span class='<?php echo $levelClass;?>'><?php if($charter->level) echo zget($lang->charter->levelList, $charter->level);?></span></strong></td>
                    <th class="c-market"><?php echo $lang->charter->market;?></th>
                    <td><strong><?php echo zget($lang->charter->marketList, $charter->market);?></strong></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->charter->appliedBy;?></th>
                    <td><strong><?php echo zget($users, $charter->appliedBy);?></strong></td>
                    <th><?php echo $lang->charter->budget?></th>
                    <td><strong><?php echo $charter->budget;?></strong></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><?php echo $lang->charter->productRoadmap;?></h2>
          </div>
          <div class="detail">
            <div class="detail-content article-content">
              <?php if(empty($groupDate)):?>
              <div class="table-empty-tip">
                <p>
                  <span class="text-muted"><?php echo $lang->charter->noData;?></span>
                </p>
              </div>
              <?php else:?>
              <table class="table table-hover table-fixed">
                <thead>
                  <tr class='text-center'>
                    <th class='w-80px'><?php echo $lang->charter->linkProduct;?></th>
                    <th class='w-150px'> <?php echo $lang->charter->linkRoadmap;?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($groupDate as $productID => $roadmapList):?>
                  <?php $productLink = $this->createLink('product', 'view', "productID=$productID");?>
                  <tr>
                    <td class='text-center' title="<?php echo zget($products, $productID);?>"><?php echo common::hasPriv('product', 'view') ? html::a($productLink, zget($products, $productID)) : zget($products, $productID);?></td>
                    <td>
                      <div class="flex">
                        <?php foreach($roadmapList as $roadmap):?>
                        <?php $roadmapLink = $this->createLink('roadmap', 'view', "roadmapID=$roadmap->id");?>
                        <div class="clip" title="<?php echo $roadmap->name;?>">
                          <?php echo common::hasPriv('roadmap', 'view') ? html::a($roadmapLink, $roadmap->name) : $roadmap->name;?>
                        </div>
                        <?php endForeach;?>
                      <div>
                    </td>
                  </tr>
                  <?php endForeach;?>
                </tbody>
              </table>
              <?php endif;?>
            </div>
          </div>
        </div>
        <?php if($lang->charter->charterList):?>
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><?php echo $lang->charter->charterFiles;?></h2>
          </div>
          <div class="detail">
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <?php $charterFiles = $charter->charterFiles;?>
                  <?php foreach($lang->charter->charterList as $key => $name):?>
                  <tr>
                    <th><?php echo $name;?></th>
                    <td>
                    <?php
                      if(isset($charterFiles[$key]))
                      {
                          $fileID = $charterFiles[$key]['id'];
                          $files  = array($fileID => $charter->files[$fileID]);
                          echo $this->fetch('file', 'printFiles', array('files' => $files, 'fieldset' => 'false', 'object' => $charter, 'method' => 'view', 'showDelete' => false));
                          unset($charterFiles[$key]);
                      }
                    ?>
                    </td>
                  </tr>
                  <?php endForeach;?>
                  <?php if($charterFiles):?>
                  <?php foreach($charterFiles as $key => $file):?>
                  <tr>
                    <th><?php echo $key;?></th>
                    <td>
                    <?php
                      if(isset($charterFiles[$key]))
                      {
                          $fileID = $charterFiles[$key]['id'];
                          $files  = array($fileID => $charter->files[$fileID]);
                          echo $this->fetch('file', 'printFiles', array('files' => $files, 'fieldset' => 'false', 'object' => $charter, 'method' => 'view', 'showDelete' => false));
                      }
                    ?>
                    </td>
                  </tr>
                  <?php endForeach;?>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if($charter->status != 'wait'):?>
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><?php echo $lang->charter->review;?></h2>
          </div>
          <div class="detail">
            <div class="detail-content">
              <table class="table table-data data-basic">
                <tbody>
                  <tr>
                    <th><?php echo $lang->charter->review;?></th>
                    <td class='<?php echo $charter->status;?>'><strong><?php echo zget($lang->charter->reviewResultList, $charter->reviewedResult);?></strong></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->charter->reviewer;?></th>
                    <td>
                      <strong>
                      <?php
                      if(!empty($charter->reviewedBy))
                      {
                          foreach(explode(',', $charter->reviewedBy) as $account)
                          {
                              if(empty($account)) continue;
                              echo "<span>" . zget($users, trim($account)) . '</span> &nbsp;';
                          }
                      }
                      ?>
                      </strong>
                    </td>
                  </tr>
                  <?php if(!helper::isZeroDate($charter->meetingDate)):?>
                  <tr>
                    <th><?php echo $lang->charter->meetingDate;?></th>
                    <td><strong><?php echo helper::isZeroDate($charter->meetingDate) ? '' : $charter->meetingDate;?></strong></td>
                  </tr>
                  <?php endif;?>
                  <?php if($charter->meetingLocation):?>
                  <tr>
                    <th><?php echo $lang->charter->meetingLocation;?></th>
                    <td><strong><?php echo $charter->meetingLocation;?></strong></td>
                  </tr>
                  <?php endif;?>
                  <?php if($charter->meetingMinutes):?>
                  <tr>
                    <th><?php echo $lang->charter->meetingMinutes;?></th>
                    <td><strong><?php echo $charter->meetingMinutes;?></strong></td>
                  </tr>
                  <?php endif;?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endif;?>
        <div class="cell">
          <div class="detail">
            <h2 class="detail-title"><?php echo $lang->charter->roadmapStory;?></h2>
          </div>
          <div class="detail">
            <div class="detail-content article-content">
              <?php if(empty($stories)):?>
              <div class="table-empty-tip">
                <p>
                  <span class="text-muted"><?php echo $lang->charter->noData;?></span>
                </p>
              </div>
              <?php else:?>
              <table class="table table-hover table-fixed">
                <thead>
                  <tr class='text-center'>
                    <th class='w-80px' title=<?php echo $lang->story->id;?>><?php echo $lang->idAB;?></th>
                    <th class='w-100px' title=<?php echo $lang->story->pri;?>><?php echo $lang->priAB;?></th>
                    <th><?php echo $lang->story->requirement . $lang->nameAB;?></th>
                    <th class='w-150px'><?php echo $lang->story->roadmap;?></th>
                    <th class='w-150px'><?php echo $lang->story->module;?></th>
                    <th class='w-150px'> <?php echo $lang->story->status;?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($stories as $story):?>
                  <?php $storyLink = $this->createLink('story', 'storyView', "storyID=$story->id&version=$story->version&param=0&storyType=requirement", '', true);?>
                  <tr>
                    <td class='text-center'><?php echo $story->id;?></td>
                    <?php $priClass = $story->pri ? "label-pri label-pri-{$story->pri}" : '';?>
                    <td class='text-center'><span class='<?php echo $priClass;?>'><?php if($story->pri) echo $story->pri;?></span></td>
                    <td class='text-left nobr c-name' title="<?php echo $story->title?>">
                        <span class='label gray-pale'><?php echo $storyGrades["$story->type-$story->grade"]; ?></span>
                        <?php echo common::hasPriv('story', 'view') ? html::a($storyLink, $story->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'") : $story->title;?>
                    </td>
                    <td class='text-center' title="<?php echo zget($roadmaps, $story->roadmap);?>"><?php echo zget($roadmaps, $story->roadmap);?></td>
                    <td class='text-center' title="<?php echo zget($modules, $story->module);?>"><?php echo zget($modules, $story->module);?></td>
                    <td class='text-center'><?php echo zget($lang->story->statusList, $story->status);?></td>
                  </tr>
                  <?php endForeach;?>
                </tbody>
              </table>
              <?php endif;?>
            </div>
          </div>
        </div>
        <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=charter&objectID=$charter->id");?>
        <div class="cell"><?php include $app->getModuleRoot() . 'common/view/action.html.php';?></div>
      </div>
    </div>

    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php
        $browseLink = $this->session->charterList ? $this->session->charterList : inlink('browse');
        common::printBack($browseLink);

        if(!$charter->deleted) echo $this->charter->buildOperateMenu($charter, 'view');
        ?>
      </div>
    </div>
  </div>
</div>
<div id="mainActions" class='main-actions'>
  <nav class="container"></nav>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
