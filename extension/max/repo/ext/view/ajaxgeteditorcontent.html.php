<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Yanyi Cao
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php
include $app->getModuleRoot() . 'common/view/header.lite.html.php';
include $app->getModuleRoot() . 'common/view/form.html.php';
include $app->getModuleRoot() . 'common/view/kindeditor.html.php';
include 'header.review.html.php';
$canLinkStory    = common::hasPriv('repo', 'linkStory');
$canLinkBug      = common::hasPriv('repo', 'linkBug');
$canLinkTask     = common::hasPriv('repo', 'linkTask');
$canUnlinkObject = common::hasPriv('repo', 'unlink');
$canAddBug       = common::hasPriv('repo', 'addBug');

js::set('jsRoot', $jsRoot);
js::set('clientLang', $app->clientLang);
js::set('fileExt', $this->config->repo->fileExt);
js::set('file', $pathInfo);
js::set('blameTmpl', $lang->repo->blameTmpl);
js::set('repoID', $repoID);
js::set('showEditor', $showEditor);
js::set('canLinkStory', $canLinkStory);
js::set('canLinkBug', $canLinkBug);
js::set('canLinkTask', $canLinkTask);
js::set('objectID', 0);
js::set('objectType', 'story');
js::set('pageType', $type);
js::set('revision', $revision);
js::set('sourceRevision', $oldRevision);
js::set('encodePath', $this->repo->encodePath($entry));
js::set('canAddBug', $canAddBug);
js::set('createLang', $lang->repo->addIssue);
if($showEditor) js::set('codeContent', $content);
js::import($jsRoot . '/zui/tabs/tabs.min.js');
js::import($jsRoot . 'monaco-editor/min/vs/loader.js');
?>
<div id="monacoEditor" class='repoCode'>
  <?php if(strpos($config->repo->images, "|$suffix|") !== false):?>
  <div class='image'><img src='data:image/<?php echo $suffix?>;base64,<?php echo $content?>' /></div>
  <?php elseif($suffix == 'binary'):?>
  <div class='binary'><?php echo html::a($this->repo->createLink('download', "repoID=$repoID&path=" . $this->repo->encodePath($entry) . "&fromRevision=$revision"), "<i class='icon-download'></i>", 'hiddenwin', "title='{$lang->repo->download}'"); ?></div>
  <?php else:?>
  <div id="codeContainer"></div>
  <?php endif;?>
  <div id="log">
    <div class="history"></div>
    <div class="pull-right action-btn">
      <div class="btn btn-close pull-right"><i class="icon icon-close"></i></div>
      <?php if($canLinkStory or $canLinkBug or $canLinkTask):?>
      <div class="dropdown pull-right">
        <button class="btn" type="button" data-toggle="context-dropdown"><i class="icon icon-ellipsis-v icon-rotate-90"></i></button>
        <ul class="dropdown-menu">
          <?php
          if($canLinkStory) echo '<li id="linkStory">' . html::a('javascript:;', '<i class="icon icon-lightbulb"></i> ' . $lang->repo->linkStory) . '</li>';
          if($canLinkBug) echo '<li id="linkBug">' . html::a('javascript:;', '<i class="icon icon-bug"></i> ' . $lang->repo->linkBug) . '</li>';
          if($canLinkTask) echo '<li id="linkTask">' . html::a('javascript:;', '<i class="icon icon-todo"></i> ' . $lang->repo->linkTask) . '</li>';
          ?>
        </ul>
      </div>
      <?php endif;?>
    </div>
  </div>
  <div id="related">
    <div class="main-col main">
      <div class="content panel">
        <div class='btn-toolbar'>
          <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
          <div class="btn btn-right pull-right"><i class="icon icon-chevron-right"></i></div>
          <div class='panel-title'>
            <div class="tabs w-10" id="relationTabs"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="table-empty-tip">
      <p><?php echo $lang->repo->notRelated;?></p>
    </div>
  </div>
</div>
<div id='reviewBugs' class='hidden'>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
    <h4 class="modal-title"><?php echo $lang->repo->viewBugs;?></h4>
  </div>
  <div class='review-bug-list'></div>
</div>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
<script>
var editor         = null;
var viewZoneId     = null;
var bugViewZoneId  = null;
var modifiedEditor = null;
var diffContent    = null;
var blames         = null;
var globalCommit   = '';
var codeHeight     = $(window).innerHeight() - $('#mainHeader').height() - $('#appsBar').height() - $('#fileTabs .tabs-navbar').height();
var editorWidth    = $(window).innerWidth() - 150;
var zoneLeft       = '65px'
if(codeHeight > 0) $.cookie('codeContainerHeight', codeHeight);
$('#codeContainer').css('height', $.cookie('codeContainerHeight'));

/**
 * Get relation by commit.
 *
 * @param  string $commit
 * @access public
 * @return void
 */
function getRelation(commit)
{
    $('.table-empty-tip').show();
    $('#codeContainer').css('height', codeHeight / 5 * 3);
    var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 10;
    $('#related').css('height', relatedHeight);
    $tabs = $('#relationTabs').data('zui.tabs');
    if($tabs) $tabs.closeAll();

    $.post(createLink('repo', 'ajaxGetCommitRelation', 'repoID=' + repoID + '&commit=' + commit), function(data)
    {
        var titleList = JSON.parse(data).titleList;
        var tabs = [];
        $.each(titleList, function(i, titleObj)
        {
            var tab = setTab(titleObj);
            if($tabs)
            {
                $tabs.open(tab);
            }
            else
            {
                tabs.push(tab);
            }
        });

        if(titleList.length > 0)
        {
            if($tabs)
            {
                $tabs.open(setTab(titleList[0]));
            }
            else
            {
                $('#relationTabs').tabs({tabs: tabs});
            }

            $('.table-empty-tip').hide();
            $('#related button').show();
        }
        else
        {
            if(!canLinkStory && !canLinkBug && !canLinkTask) $('#related button').hide();
        }

        arrowTabs('relationTabs', 1);
    });

    globalCommit  = commit;
    var linkStory = createLink('repo', 'linkStory', 'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkBug   = createLink('repo', 'linkBug',   'repoID=' + repoID + '&commit=' + commit, '', true);
    var linkTask  = createLink('repo', 'linkTask',  'repoID=' + repoID + '&commit=' + commit, '', true);
    $('#linkStory a').attr('data-link', linkStory);
    $('#linkBug a').attr('data-link', linkBug);
    $('#linkTask a').attr('data-link', linkTask);
    $('#related').show();
}

<?php if($canUnlinkObject):?>
$('#relationTabs').on('onLoad', function(event, tab) {
    var objectInfo = tab.id.split('-');
    objectID       = objectInfo[0];
    objectType     = objectInfo[1];
    unlink = createLink('repo', 'unlink',  'repoID=' + repoID + '&commit=' + globalCommit + '&objectID=' + objectID + '&objectType=' + objectType);
    $('#relationTabs ul li[data-id=' + tab.id + '] span.title').after('<a title="<?php echo $lang->repo->unlink;?>" class="unlinks" data-link="' + unlink + '"><i class="icon-unlink"></i></a>');
});
<?php endif;?>

/**
 * Set tab data.
 *
 * @param  object $titleObj
 * @access public
 * @return object
 */
function setTab(titleObj)
{
    return {
        id:    titleObj.type + '-' + titleObj.id,
        title: titleObj.title,
        icon:  titleObj.type == 'story' ? 'icon-lightbulb' : (titleObj.type == 'task' ? 'icon-check-sign' : 'icon-bug'),
        type:  'iframe',
        url:   createLink('repo', 'ajaxGetRelationInfo', 'objectID=' + titleObj.id + '&objectType=' + titleObj.type)
    };
}

/**
 * Init bugs.
 *
 * @access public
 * @return void
 */
function initBugs()
{
    var selector = '#codeContainer .margin-view-overlays > div';
    if(pageType == 'diff') selector = '#codeContainer .modified-in-monaco-diff-editor .margin-view-overlays > div';
    $.each($(selector), function(i)
    {
        var lineNumber = $(this).find('.line-numbers').text();
        $(this).addClass('line-number-' + lineNumber).addClass('content-line').attr('data-line', lineNumber);
        if(!$(this).find('.add-bug').length) $(this).append('<span class="view-line-icon text-primary add-bug hidden icon-create-' + lineNumber + '" data-line="' + lineNumber + '" title="' + createLang + '"><i class="icon icon-plus-bold text-primary strong"></i></span>');

        var $viewLine = pageType == 'diff' ? $('.modified .view-lines:not(".line-delete")') : $('.view-lines');
        $viewLine.find('.view-line').eq(i).attr('class', '').addClass('view-line-' + lineNumber + ' view-line view-content-line').attr('data-line', lineNumber);
    });

    if(bugs)
    {
        var lineBugs, bugsCount, i, hasBugIcon, $lineNumberDom;
        for(var line in bugs)
        {
            if(line)
            {
                lineBugs  = bugs[line];
                bugsCount = lineBugs.length;

                var $viewNumberDom = pageType == 'diff' ? $('.modified .view-lines .view-line-' + line) : $('.view-lines .view-line-' + line);
                if($viewNumberDom.find('.view-line-icon .bug-count').length) $viewNumberDom.find('.view-line-icon.view-bugs').remove();
                $viewNumberDom.append('<span class="view-line-icon view-bugs text-primary icon-bug-' + line + '" data-line="' + line + '"><i class="icon icon-audit"></i><strong class="bug-count">' + bugsCount + '</strong></span>');
            }
        }
    }
}

/**
 * Update diff editor inline style.
 *
 * @param  bool   display
 * @access public
 * @return void
 */
function updateEditorInline(display){
    if(display)
    {
        zoneLeft    = '20px';
        editorWidth = $(window).innerWidth() / 2;
    }
    else
    {
        zoneLeft    = '50px';
        editorWidth = $(window).innerWidth() - 150;
    }
    modifiedEditor.updateOptions({renderSideBySide: display});
    setTimeout(initBugs, 100);
}

/**
 * Show commit info.
 *
 * @access public
 * @return void
 */
function showCommitInfo()
{
    var link = createLink('repo', 'ajaxGetCommitInfo');
    var data = {
        repoID        : repoID,
        entry         : encodePath,
        revision      : revision,
        sourceRevision: sourceRevision,
        line          : 0,
        returnType    : 'json'
    };

    $.post(link, data, function(res)
    {
        if(!res) return;

        res    = JSON.parse(res);
        blames = res.blames;
    })
}

/**
 * Show blame info and relations.
 *
 * @param  int    line
 * @access public
 * @return void
 */
function showBlameAndRelation(line)
{
    /* Show bugs in this line. */
    if(!blames) return;

    if(diffContent)
    {
        var lineIndex = jQuery.inArray(line, diffContent.line.new);
        line = diffContent.line.new[lineIndex];
    }

    var blame = blames[line];
    if(!blame) return;

    var p_line = parseInt(line);
    while(!blame.revision)
    {
        p_line--;
        blame = blames[p_line];
    }
    if($('#log').data('line') == p_line) return;

    var time    = blame.time != 'unknown' ? blame.time : '';
    var user    = blame.committer != 'unknown' ? blame.committer : '';
    var version = blame.revision.toString().substring(0, 10);
    var content = blameTmpl.replace('%line', line).replace('%time', time).replace('%name', user).replace('%version', version).replace('%comment', blame.message);
    $('.history').html(content);
    $('#log').data('line', line);
    $('#log').css('display', 'flex');
    getRelation(blame.revision);
}

$(function()
{
    $('.btn-left').click(function()  {arrowTabs('relationTabs', 1);});
    $('.btn-right').click(function() {arrowTabs('relationTabs', -2);});

    if(showEditor)
    {
        require.config({
            paths: {vs: jsRoot + 'monaco-editor/min/vs'},
            'vs/nls': {
                availableLanguages: {
                    '*': clientLang
                }
            }
        });

        require(['vs/editor/editor.main'], function ()
        {
            var lang = 'php';
            $.each(fileExt, function(langName, ext)
            {
                if(ext.indexOf('.' + file.extension) !== -1) lang = langName;
            });

            if(pageType == 'diff')
            {
                diffContent = parent.getDiffs(file.dirname + '/' + file.basename);
                modifiedEditor = monaco.editor.createDiffEditor(document.getElementById('codeContainer'),
                {
                    folding:         false,
                    language:        lang,
                    readOnly:        true,
                    autoIndent:      true,
                    contextmenu:     true,
                    automaticLayout: true,
                    minimap:         {enabled: false},
                    showFoldingControls: 'never',
                    lineNumbers: function(number)
                    {
                        var newlc = diffContent.line.new;
                        var oldlc = diffContent.line.old;
                        return newlc[number - 1];
                    }
                });

                modifiedEditor.setModel({
                    original: monaco.editor.createModel(diffContent.code.old.trim("\n"), lang),
                    modified: monaco.editor.createModel(diffContent.code.new.trim("\n"), lang),
                });

                editor = modifiedEditor.getModifiedEditor();

                var getOriginalEditor = modifiedEditor.getOriginalEditor();
                getOriginalEditor.onMouseDown(function(obj)
                {
                    showBlameAndRelation(obj.target.position.lineNumber);
                })
            }
            else
            {
                editor = monaco.editor.create(document.getElementById('codeContainer'),
                {
                    value:           codeContent.toString(),
                    folding:         false,
                    language:        lang,
                    readOnly:        true,
                    autoIndent:      true,
                    contextmenu:     true,
                    automaticLayout: true,
                    minimap:         {enabled: false},
                    showFoldingControls: 'never'
                });
            }

            editor.onDidScrollChange(function()
            {
                setTimeout(initBugs, 100);
            });

            editor.addAction({
                id: "editor.action.addBug",
                label: createLang,
                contextMenuGroupId: 'customCommand',
                run() {
                    var position = editor.getPosition();
                    showBugForm(position.lineNumber)
                }
            });

            editor.onMouseMove(function(obj)
            {
                var line = obj.target.position ? obj.target.position.lineNumber : 0;
                if($(obj.target.element).closest('.view-content-line').length) line = $(obj.target.element).closest('.view-content-line').attr('data-line');
                if($(obj.target.element).closest('.content-line').length) line = $(obj.target.element).closest('.content-line').attr('data-line');

                if($(obj.target.element).parent().hasClass('view-line-icon')) return;
                if($(obj.target.element).hasClass('add-bug')) return;

                $('.content-line .add-bug').addClass('hidden');
                $('.line-number-' + line + ' .add-bug').removeClass('hidden');
            });

            editor.onMouseDown(function(obj)
            {
                closeRelation();

                var line = obj.target.position ? obj.target.position.lineNumber : $(obj.target.element).closest('.view-content-line').data('line');
                if($(obj.target.element).hasClass('icon-plus-bold') || $(obj.target.element).closest('.add-bug').length)
                {
                    showBugForm(line);
                    return;
                }

                if($(obj.target.element).parent().hasClass('view-line-icon'))
                {
                    showBugs(line);
                    return;
                }

                if(!line || $('div[widgetid="bugForm' + line + '"]').html()) return;

                hiddenForm();
                showBlameAndRelation(line);
                setTimeout(initBugs, 100);
            });

            setTimeout(function()
            {
                initBugs();

                var hash = parent.location.hash;
                if(hash)
                {
                    var reg = "^#L\\d+$";
                    if(hash.match(reg))
                    {
                        var line = parseInt(hash.substr(2));
                        if(pageType == 'diff') line = jQuery.inArray(line, diffContent.line.new) + 1;
                    }
                }
            }, 500);
        });
    }

    $('#linkStory a, #linkBug a, #linkTask a').on('click', function()
    {
        var link = $(this).attr('data-link');
        parent.loadLinkPage(link);
    });

    $('#relationTabs').on('click', '.unlinks',function()
    {
        var link  = $(this).attr('data-link');
        var tabID = $(this).attr('data-tabID');
        $.post(link, function(data)
        {
            data = JSON.parse(data);
            if(data.result)
            {
                $tabs = $('#relationTabs').data('zui.tabs');
                if($tabs) $tabs.close(tabID);
                $('#relationTabs #tab-nav-item-' + tabID).remove();
                getRelation(data.revision);
            }
            else
            {
                alert(data.message);
            }
        })
    });

    $('#relationTabs').on('onOpen', function(event, tab)
    {
        $('#tab-nav-item-' + tab.id).attr('title', tab.title);

        var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 45;
        $('#relationTabs iframe').css('height', relatedHeight);
    });

    /* Get file commits. */
    showCommitInfo();
});
</script>
