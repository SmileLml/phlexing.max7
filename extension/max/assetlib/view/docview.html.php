<?php
/**
 * The docview file of assetlib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     assetlib
 * @version     $Id: docview.html.php 4952 2021-06-30 09:18:58Z tsj $
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete);?>
<?php $sessionString = session_name() . '=' . session_id();?>
<style> .title {max-width: 80%;} </style>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($browseLink, '<i class="icon icon-back icon-sm"></i>' . $lang->goback, '', 'class="btn btn-secondary"');?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell">
      <div class="detail no-padding">
        <div class="detail-title no-padding doc-title">
          <div class="title text-ellipsis"><?php echo $doc->title;?></div>
          <div class="info">
            <div class="version">
              <div class='btn-group'>
              <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;">
                #<?php echo $version ? $version : $doc->version;?>
                <span class="caret"></span>
              </a>
                <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
                <?php
                for($version = $doc->version; $version > 0; $version--)
                {
                    $viewMethod = $objectType == 'practice' ? 'practiceView' : 'componentView';
                    echo "<li>" . html::a($this->createLink('assetlib', $viewMethod, "id=$doc->id&version=$version"), '#' . $version, '', "data-app='{$app->tab}'") . "</li>";
                }
                ?>
                </ul>
              </div>
            </div>
            <div class="user"></div>
            <div class="time"></div>
          </div>
          <div class="actions">
            <?php
            $editMethod    = $objectType == 'practice' ? 'editPractice' : 'editComponent';
            $approveMethod = $objectType == 'practice' ? 'approvePractice' : 'approveComponent';
            $removeMethod  = $objectType == 'practice' ? 'removePractice' : 'removeComponent';
            if(common::hasPriv('assetlib', $editMethod)) echo html::a(inlink($editMethod, "docID=$doc->id"), '<i class="icon-edit"></i>', '', "title='{$lang->assetlib->edit}' class='btn btn-link'");
            if(common::hasPriv('assetlib', $approveMethod) and $doc->status == 'draft') echo html::a($this->inlink($approveMethod, "docID=$doc->id", '', true), '<i class="icon-glasses"></i>', '', "title='{$lang->assetlib->approve}' class='btn btn-link iframe'");
            if(common::hasPriv('assetlib', $removeMethod))
            {
                $deleteURL = $this->createLink('assetlib', $removeMethod, "docID=$doc->id&confirm=no");
                echo html::a($deleteURL, '<i class="icon-unlink"></i>', 'hiddenwin', "title='{$lang->assetlib->remove}' class='btn btn-link'");
            }
            ?>
          </div>
        </div>
        <div class="detail-content article-content">
          <?php
          if($doc->type == 'url')
          {
              $url = $doc->content;
              if(!preg_match('/^https?:\/\//', $doc->content)) $url = 'http://' . $url;
              $urlIsHttps = strpos($url, 'https://') === 0;
              $serverIsHttps = ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https'));
              if(($urlIsHttps and $serverIsHttps) or (!$urlIsHttps and !$serverIsHttps))
              {
                  echo "<iframe width='100%' id='urlIframe' src='$url'></iframe>";
              }
              else
              {
                  $parsedUrl = parse_url($url);
                  $urlDomain = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

                  $title    = '';
                  $response = common::http($url);
                  preg_match_all('/<title>(.*)<\/title>/Ui', $response, $out);
                  if(isset($out[1][0])) $title = $out[1][0];

                  echo "<div id='urlCard'>";
                  echo "<div class='url-icon'><img src='{$urlDomain}/favicon.ico' width='45' height='45' /></div>";
                  echo "<div class='url-content'>";
                  echo "<div class='url-title'>{$title}</div>";
                  echo "<div class='url-href'>" . html::a($url, $url, '_target') . "</div>";
                  echo "</div></div>";
              }
          }
          elseif($doc->contentType == 'markdown')
          {
              echo "<textarea id='markdownContent'></textarea>";
          }
          else
          {
              echo $doc->content;
          }
          ?>
          <?php foreach($doc->files as $file):?>
          <?php if(in_array($file->extension, $config->file->imageExtensions)):?>
          <div class='file-image'>
            <a href="<?php echo $file->webPath?>" target="_blank">
              <img onload="setImageSize(this, 0)" src="<?php echo $this->createLink('file', 'read', "fileID={$file->id}");?>" alt="<?php echo $file->title?>" title="<?php echo $file->title;?>">
            </a>
            <span class='right-icon'>
              <?php
              $downloadLink  = $this->createLink('file', 'download', 'fileID=' . $file->id);
              $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
              $downloadLink .= $sessionString;
              ?>
              <?php if(common::hasPriv('file', 'download')) echo html::a($downloadLink, "<i class='icon icon-export'></i>", '', "class='btn-icon' style='margin-right: 10px;' title=\"{$lang->doc->download}\"");?>
              <?php if(common::hasPriv('doc', 'deleteFile')) echo html::a('###', "<i class='icon icon-trash'></i>", '', "class='btn-icon' title=\"{$lang->doc->deleteFile}\" onclick='deleteFile($file->id)'");?>
            </span>
          </div>
          <?php unset($doc->files[$file->id]);?>
          <?php endif;?>
          <?php endforeach;?>
        </div>
      </div>
      <?php echo $this->fetch('file', 'printFiles', array('files' => $doc->files, 'fieldset' => 'true', 'object' => $doc));?>
    </div>
    <div class='cell'>
      <?php $actionFormLink = $this->createLink('action', 'comment', "objectType=doc&objectID=$doc->id");?>
      <?php include $app->getModuleRoot() . 'common/view/action.html.php';?>
    </div>
  </div>
  <div class="side-col col-4" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-right"></i></div>
    <?php if(!empty($doc->digest)):?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->digest;?></summary>
        <div class="detail-content">
          <?php echo !empty($doc->digest) ? $doc->digest : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </details>
    </div>
    <?php endif;?>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->keywords;?></summary>
        <div class="detail-content">
          <?php echo !empty($doc->keywords) ? $doc->keywords : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
        </div>
      </details>
    </div>
    <div class="cell">
      <details class="detail" open>
        <summary class="detail-title"><?php echo $lang->doc->basicInfo;?></summary>
        <div class="detail-content">
          <table class="table table-data">
            <tbody>
              <?php $widthClass = common::checkNotCN() ? 'w-100px' : 'w-80px';?>
              <tr>
                <th class='<?php echo $widthClass;?>'><?php echo $lang->assetlib->sourceDoc;?></th>
                <td><?php echo html::a($this->createLink('doc', 'view', "docID={$doc->from}&version=$doc->fromVersion"), $source->title, '', "data-app='project'")?></td>
              </tr>
              <tr>
                <th><?php echo $lang->assetlib->importedBy;?></th>
                <td><?php echo zget($users, $doc->addedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->assetlib->importedDate;?></th>
                <td><?php echo $doc->addedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->assetlib->approvedBy;?></th>
                <td><?php if($doc->status == 'active') echo zget($users, $doc->assignedTo);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->assetlib->approvedDate;?></th>
                <td><?php echo helper::isZeroDate($doc->approvedDate) ?  '' : $doc->approvedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedBy;?></th>
                <td><?php echo zget($users, $doc->editedBy);?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->editedDate;?></th>
                <td><?php echo helper::isZeroDate($doc->editedDate) ?  '' : $doc->editedDate;?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </details>
    </div>
  </div>
</div>
<?php if($doc->contentType == 'markdown'):?>
<?php css::import($jsRoot . "markdown/simplemde.min.css");?>
<?php js::import($jsRoot . 'markdown/simplemde.min.js'); ?>
<?php js::set('markdownText', htmlspecialchars($doc->content));?>
<script>
$(function()
{
    var simplemde = new SimpleMDE({element: $("#markdownContent")[0],toolbar:false, status: false});
    simplemde.value(String(markdownText));
    simplemde.togglePreview();

    $('#content .CodeMirror .editor-preview a').attr('target', '_blank');
})
</script>
<?php endif;?>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
