<?php
/**
 * The diff view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: browse.html.php $
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/form.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php include 'header.review.html.php';?>
<?php js::set('revision', $newRevision);?>
<?php js::set('sourceRevision', $oldRevision);?>
<?php js::set('repoID', $repoID);?>
<?php js::set('arrange', $arrange);?>
<?php js::set('repo', $repo);?>
<?php js::set('repoLang', $lang->repo);?>
<?php js::set('objectID', $objectID);?>
<?php js::set('edition', $config->edition);?>
<?php
$browser = helper::getBrowser();
js::set('browser', $browser['name']);
?>
<?php $titleWidth = common::checkNotCN() ? '240' : '210';?>
<?php if(!isonlybody()):?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left repo-diff'>
    <?php if($diffs):?>
    <div class="pull-left" id="sidebarHeader" style="width: <?php echo $titleWidth;?>px">
      <div class="title" title="<?php echo $lang->repo->changeFile;?>"><?php echo $lang->repo->changeFile;?></div>
    </div>
    <?php
    else:
    $backURI = $this->session->repoView ? $this->session->repoView : $this->session->repoList;
    if($backURI)
    {
        echo html::a($backURI, "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-link' data-app='{$app->tab}'");
    }
    else
    {
        echo html::backButton("<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "btn btn-link");
    }
    ?>
    <?php endif;?>

    <div class="page-title">
      <?php if($repo->SCM == 'Subversion'):?>
      <div class="svn-path">
      <?php
      echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=&objectID=$objectID"), $repo->name, '', "data-app='{$app->tab}'");
      $paths    = explode('/', $entry);
      $fileName = array_pop($paths);
      $postPath = '';
      foreach($paths as $pathName)
      {
          $postPath .= $pathName . '/';
          echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'), '', "data-app='{$app->tab}'");
      }

      $oldRevision = $oldRevision == '^' ? $newRevision - 1 : $oldRevision;
      echo "/ $fileName ";
      ?>
      </div>
      <form class='main-table pull-right' id="svnForm" data-ride='table'>
        <input type="hidden" name="repoID" id="repoID" value="<?php echo $repoID;?>">
        <?php echo html::input("oldRevision", $oldRevision, "id='oldRevision' class='form-control svn-version' placeholder='{$lang->repo->source}'");?>
        <span class="label label-exchange"><i class="icon icon-exchange"></i></span>
        <?php echo html::input("newRevision", $newRevision, "id='newRevision' class='form-control svn-version' placeholder='{$lang->repo->target}'");?>
        <input type="hidden" name="isBranchOrTag" id="isBranchOrTag" value="">
        <?php echo html::commonButton($lang->repo->compare, '', 'btn btn-primary diffForm');?>
      </form>
      <?php else:?>
      <div class='btn-group' id='sourceSwapper'>
        <button data-toggle='dropdown' type='button' class='btn btn-link btn-limit repo-select text-ellipsis'title='<?php echo $oldRevision ? $oldRevision : $lang->repo->select;?>'>
          <span class='text pull-left'><?php echo $lang->repo->source . ': ';?></span>
          <span class='text version-name pull-left text-ellipsis' <?php echo common::checkNotCN() ? '' : 'style="max-width: 110px;"';?>><?php echo $oldRevision;?></span>
          <span class='caret' style='margin-bottom: -1px'></span>
        </button>
        <div id='dropMenuSource' class='dropdown-menu search-list' data-ride='searchList' data-url=''>
          <div class="input-control search-box has-icon-left has-icon-right search-example">
          <input type="search" class="form-control search-input" id="searchSource" placeholder="<?php echo $lang->repo->searchPlaceholder;?>"/>
            <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
            <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
          </div>
          <div class="table-row">
            <div class="table-col col-left">
              <div class="list-group" id="sourceList"></div>
            </div>
          </div>
        </div>
      </div>
      <span class="label label-exchange-git"><i class="icon icon-exchange"></i></span>
      <div class='btn-group' id='targetSwapper'>
        <button data-toggle='dropdown' type='button' class='btn btn-link btn-limit repo-select text-ellipsis' title='<?php echo $newRevision ? $newRevision : $lang->repo->select;?>'>
          <span class='text pull-left'><?php echo $lang->repo->target . ': ';?></span>
          <span class='text version-name pull-left text-ellipsis' <?php echo common::checkNotCN() ? '' : 'style="max-width: 110px;"';?>><?php echo $newRevision;?></span>
          <span class='caret' style='margin-bottom: -1px'></span>
        </button>
        <div id='dropMenuTarget' class='dropdown-menu search-list' data-ride='searchList' data-url=''>
          <div class="input-control search-box has-icon-left has-icon-right search-example">
            <input type="search" class="form-control search-input" id="searchTarget" placeholder="<?php echo $lang->repo->searchPlaceholder;?>"/>
            <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
            <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
          </div>
          <div class="table-row">
            <div class="table-col col-left">
              <div class="list-group" id="targetList"></div>
            </div>
          </div>
        </div>
      </div>
      <?php endif;?>
      <?php if($repo->SCM != 'Subversion'):?>
      <form class='main-table pull-right' id="gitForm" data-ride='table'>
        <input type="hidden" name="repoID" id="repoID" value="<?php echo $repoID;?>">
        <input type="hidden" name="oldRevision" id="oldRevision" value="<?php echo $oldRevision;?>">
        <input type="hidden" name="newRevision" id="newRevision" value="<?php echo $newRevision;?>">
        <input type="hidden" name="isBranchOrTag" id="isBranchOrTag" value="1">
        <?php echo html::commonButton($lang->repo->compare, '', 'btn btn-primary diffForm');?>
      </form>
      <?php endif;?>
    </div>
  </div>
</div>
<?php endif;?>
<?php if($browser['name'] == 'ie'):?>
<?php if($oldRevision != '' and $newRevision != ''):?>
<div class="fade main-row split-row" id="mainRow">
  <?php if($diffs):?>
  <?php $sideWidth = common::checkNotCN() ? '260' : '230';?>
  <div class="side-col" style="width: <?php echo $sideWidth;?>px; padding-top: <?php echo isonlybody() ? 22 : 0;?>px;">
    <?php if(isonlybody()):?>
    <div id="sidebarHeader" style="width: <?php echo $titleWidth;?>px">
      <div class="title" title="<?php echo $lang->repo->changeFile;?>"><?php echo $lang->repo->changeFile;?></div>
    </div>
    <?php endif;?>
    <div class="side-col file-tree" style="width: <?php echo $sideWidth;?>px;" data-min-width="<?php echo $sideWidth;?>">
        <div class="cell <?php if(isonlybody()) echo 'pull-left';?>">
        <?php echo $this->repo->getDiffFileTree($diffs);?>
      </div>
    </div>
  </div>
  <?php endif;?>
  <div class="repo panel cell main-col pull-left col-12">
    <div class='panel-heading'>
      <form method='post'>
        <div class='btn-group pull-right'>
          <?php echo html::commonButton($lang->repo->viewDiffList['inline'], "id='inline'", $arrange == 'inline' ? 'active btn btn-sm' : 'btn btn-sm')?>
          <?php echo html::commonButton($lang->repo->viewDiffList['appose'], "id='appose'", $arrange == 'appose' ? 'active btn btn-sm' : 'btn btn-sm')?>
        </div>
        <div class='btn-toolbar'>
          <div class='btn-group'>
            <?php
            if(common::hasPriv('repo', 'download'))
            {
                $oldVersion = $isBranchOrTag ? helper::safe64Encode(urlencode($oldRevision)) : $oldRevision;
                $newVersion = $isBranchOrTag ? helper::safe64Encode(urlencode($newRevision)) : $newRevision;
                echo html::a($this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevison=$oldVersion&toRevision=$newVersion&type=path&isBranchOrTag=$isBranchOrTag"), $lang->repo->downloadDiff, 'hiddenwin', "class='btn btn-sm btn-download'");
            }
            ?>
            <div class='btn-group'>
              <?php echo html::commonButton(zget($lang->repo->encodingList, $encoding, $lang->repo->encoding) . "<span class='caret'></span>", "data-toggle='dropdown'", 'btn dropdown-toggle btn-sm')?>
              <ul class='dropdown-menu' role='menu'>
                <?php foreach($lang->repo->encodingList as $key => $val):?>
                <li><?php echo html::a('javascript:changeEncoding("'. $key . '")', $val)?></li>
                <?php endforeach;?>
              </ul>
            </div>
          </div>
        </div>
        <?php echo html::hidden('arrange', $arrange) . html::hidden('encoding', $encoding) . html::hidden('revision[]', helper::safe64Encode(urlencode($newRevision))) . html::hidden('revision[]', helper::safe64Encode(urlencode($oldRevision))) . html::hidden('isBranchOrTag', 1);?>
      </form>
    </div>
    <div class='btn-group header-btn' id="fileSwapper">
      <?php $fileCount = count($diffs);?>
      <button <?php if($fileCount > 0) echo 'data-toggle="dropdown"';?> type='button' class='btn'>
      <span class='text'><?php echo sprintf($lang->repo->fileTotal, $fileCount);?></span>
        <span class='caret' style='margin-bottom: -1px'></span>
      </button>
      <div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList'>
        <div class="input-control search-box has-icon-left has-icon-right search-example">
          <input type="search" class="form-control search-input" />
          <label class="input-control-icon-left search-icon">
            <i class="icon icon-search"></i>
          </label>
          <a class="input-control-icon-right search-clear-btn">
            <i class="icon icon-close icon-sm"></i>
          </a>
        </div>
        <div class="list-group">
          <div class="table-row">
            <div class="table-col col-left">
              <div class="list-group">
                <ul class="tree tree-simple" data-ride="tree" id="fileTree" data-name="tree-program">
                  <?php $deletedLine = $addedLine = 0;?>
                  <?php foreach($diffs as $key => $diffFile):?>
                  <?php if(isset($diffFile->contents)):?>
                  <?php foreach($diffFile->contents as $content):?>
                  <?php foreach($content->lines as $line):?>
                  <?php if($line->type == 'old') $deletedLine ++;?>
                  <?php if($line->type == 'new') $addedLine ++;?>
                  <?php endforeach;?>
                  <?php endforeach;?>
                  <?php endif;?>
                  <li><a href="#filePath<?php echo $key;?>" class="text-ellipsis fileName search-list-item" onclick="jumpFile('<?php echo $key;?>', this)" title="<?php echo $diffFile->fileName;?>" data-key="<?php echo $diffFile->fileName;?>"><?php echo $diffFile->fileName;?></a></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class='code-survey'><?php echo sprintf($lang->repo->codeSurvey, $addedLine, $deletedLine);?></div>
    <?php foreach($diffs as $key => $diffFile):?>
    <div class='repoCode'>
      <a name="filePath<?php echo $key;?>" href="#"></a>
      <table class='table diff' id='file<?php echo $key;?>'>
        <caption>
          <i class="icon list-toggle list-toggle-open" onclick="diffToggle(this)"></i>
          <a class="file-name"><?php echo $diffFile->fileName;?></a>
        </caption>
        <?php if(empty($diffFile->contents)) continue;?>
        <?php if($repo->SCM == 'Subversion') $diffFile->fileName = $fileName . '/' . $diffFile->fileName;?>
        <?php foreach($diffFile->contents as $content):?>
        <?php
        $oldCurrentLine = $content->oldStartLine;
        $newCurrentLine = $content->newStartLine;
        ?>
        <?php if(!in_array($oldCurrentLine, array('0', '1')) and !in_array($newCurrentLine, array('0', '1'))):?>
        <tr data-line='<?php echo $newCurrentLine ?>' class='empty'>
          <th class='w-num text-center'>...</th>
          <?php if($arrange == 'appose'):?>
          <td class='none code'></td>
          <?php endif;?>
          <th class='w-num text-center'>...</th>
          <td class='none code'></td>
        </tr>
        <?php endif?>
        <?php if($arrange == 'inline'):?>
        <?php foreach($content->lines as $line):?>
        <tr data-line='<?php echo $line->type == 'old' ? $line->oldlc : $line->newlc;?>' data-src='<?php echo helper::safe64Encode(urlencode($diffFile->fileName));?>' data-type='<?php echo $line->type;?>'>
          <th class='w-num text-center'><?php if($line->type != 'new' and $line->oldlc) echo $line->oldlc;?></th>
          <th class='w-num text-center' data-line='<?php echo $line->type == 'new' ? $line->newlc : $line->oldlc;?>'>
            <?php if($line->type != 'old') echo $line->newlc;?>
          </th>
          <td class='line-<?php echo $line->type?> code'><?php
          $line->line = $repo->SCM == 'Subversion' ? htmlspecialchars($line->line) : $line->line;
          echo $line->type == 'old' ? preg_replace('/^\-/', '&ndash;', $line->line) : ($line->type == 'new' ? $line->line : ' ' . $line->line);
          ?></td>
        </tr>
        <?php endforeach;?>
        <?php else:?>
        <?php foreach($content->lines as $line):?>
        <?php if($line->type != 'old' and !isset($content->new[$line->newlc])) continue;?>
        <tr data-line='<?php echo $line->type == 'old' ? $line->oldlc : $line->newlc;?>' data-src='<?php echo helper::safe64Encode(urlencode($diffFile->fileName));?>' data-type='<?php echo $line->type;?>'>
          <?php
          if($line->type == 'old')
          {
              $oldlc = $line->oldlc;
              $newlc = '';
              if(isset($content->new[$oldlc]) and !isset($content->new[$oldlc - 1]))
              {
                  $newlc = $line->oldlc;
                  $line->type = 'custom';
              }
          }
          else
          {
              $oldlc = $line->oldlc > 0 ? $line->oldlc : ' ';
              $newlc = $line->newlc;
          }
          ?>
          <th class='w-num text-center'><?php echo $oldlc?></th>
          <td class='w-code line-<?php if($line->type != 'new')echo $line->type?> <?php if($line->type == 'custom') echo "line-old"?> code'>
            <?php
            if(!isset($content->old[$oldlc])) $content->old[$oldlc] = '';
            $content->old[$oldlc] = $repo->SCM == 'Subversion' ? htmlspecialchars($content->old[$oldlc]) : $content->old[$oldlc];
            if(!empty($oldlc)) echo $line->type != 'all' ? preg_replace('/^\-/', '&ndash;', $content->old[$oldlc]) : ' ' . $content->old[$oldlc];
            ?>
          </td>
          <th class='w-num text-center' data-line='<?php echo $line->type == 'new' ? $line->newlc : $line->oldlc;?>'><?php echo $newlc?></th>
          <td class='w-code line-<?php if($line->type != 'old') echo $line->type?> <?php if($line->type == 'custom') echo "line-new"?> code'>
            <?php
            if(!isset($content->new[$newlc])) $content->new[$newlc] = '';
            $content->new[$newlc] = $repo->SCM == 'Subversion' ? htmlspecialchars($content->new[$newlc]) : $content->new[$newlc];
            if(!empty($newlc)) echo $line->type != 'all' ? $content->new[$newlc] : ' ' . $content->new[$newlc];
            ?>
          </td>
          <?php
          if(isset($content->old[$oldlc])) unset($content->old[$oldlc]);
          if(isset($content->new[$newlc])) unset($content->new[$newlc]);
          ?>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
        <?php endforeach;?>
      </table>
    </div>
    <?php endforeach?>
  </div>
</div>
<?php endif;?>
<?php elseif($diffs):?>
<?php include $app->getModuleRoot() . 'repo/view/diffeditor.html.php';?>
<?php endif;?>
<div class='revisions hidden'>
  <?php
  if($repo->SCM != 'Subversion')
  {
      $oldRevision = $oldRevision == '^' ? "$newRevision" : $oldRevision;
      echo " <span class='label label-info'>" . substr($oldRevision, 0, 10) . " : " . substr($newRevision, 0, 10) . ' (' . zget($historys, $oldRevision, '') . ' : ' . zget($historys, $newRevision, '') . ')</span>';
  }
  else
  {
      $oldRevision = $oldRevision == '^' ? $newRevision - 1 : $oldRevision;
      echo " <span class='label label-info'>$oldRevision : $newRevision</span>";
  }
  ?>
</div>
<form method="post" id="exchange" class="hidden">
  <input type="hidden" name="revision[]" value="<?php echo $oldRevision;?>"/>
  <input type="hidden" name="revision[]" value="<?php echo $newRevision;?>"/>
</form>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
