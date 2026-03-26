var blames        = null;
var codeHeight    = 0;
var editorWidth   = $(window).innerWidth() - 150;
var zoneLeft      = '65px';
var viewZoneId    = null;
var bugViewZoneId = null;

window.onMouseDown = function(obj)
{
    closeRelation();

    var line = obj.target.position ? obj.target.position.lineNumber : $(obj.target.element).closest('.view-content-line').data('line');
    if($(obj.target.element).hasClass('icon-plus-bold') || $(obj.target.element).closest('.add-bug').length)
    {
        showBugs(line);
        showBugForm(line);
        return;
    }

    if($(obj.target.element).parent().hasClass('view-line-icon'))
    {
        showBugs(line);
        return;
    }

    if(!line || $('div[widgetid="bugForm' + line + '"]').html()) return;

    window.hiddenForm();
    showBlameAndRelation(line);
    setTimeout(initBugs, 100);
}

window.onMouseMove = function(obj)
{
    var line = obj.target.position ? obj.target.position.lineNumber : 0;
    if($(obj.target.element).closest('.view-content-line').length) line = $(obj.target.element).closest('.view-content-line').attr('data-line');
    if($(obj.target.element).closest('.content-line').length) line = $(obj.target.element).closest('.content-line').attr('data-line');

    if(!canReview) return;
    if($(obj.target.element).parent().hasClass('view-line-icon')) return;
    if($(obj.target.element).hasClass('add-bug')) return;

    $('.content-line .add-bug').addClass('hidden');
    $('.line-number-' + line + ' .add-bug').removeClass('hidden');
}

/**
 * 修改比对差异方式。
 * Update diff editor inline style.
 *
 * @param  bool   display
 * @access public
 * @return void
 */
window.updateEditorInline = function(display)
{
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
}

/* 初始化数据 */
$(function()
{
    setTimeout(() => {
        initPage();
    }, 200);
});

/**
 * 初始化页面。
 * Init page.
 *
 * @access public
 * @return void
 */
function initPage()
{
    codeHeight = $.cookie.get('codeContainerHeight');
    $('#codeContainer').css('height', $.cookie.get('codeContainerHeight'));

    $('.btn-left').on('click',  function() {arrowTabs('relationTabs', 1);});
    $('.btn-right').on('click', function() {arrowTabs('relationTabs', -2);});

    $('#linkStory, #linkBug, #linkTask').on('click', function()
    {
        var link = $(this).data('link');
        parent.loadLinkPage(link);
    });

    $('#relationTabs').off('click', '.unlinks').on('click', '.unlinks', function()
    {
        var link  = $(this).data('link');
        var tabID = $(this).data('tabID');
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
    })

    $('#relationTabs').on('onOpen', function(event, tab)
    {
        $('#tab-nav-item-' + tab.id).attr('title', tab.title);

        var relatedHeight = codeHeight / 5 * 2 - $('#log').height() - 45;
        $('#relationTabs iframe').css('height', relatedHeight);
    });

    /* Get file commits. */
    showCommitInfo();

    if(selectedLines)
    {
        const lines = selectedLines.split(',');
        showBugs(lines[0]);
    }
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
        const lineNumber = $(this).find('.line-numbers').text();
        $(this).addClass('line-number-' + lineNumber).addClass('content-line').attr('data-line', lineNumber);
        if(!$(this).find('.add-bug').length) $(this).append('<span class="view-line-icon text-primary add-bug hidden icon-create-' + lineNumber + '" data-line="' + lineNumber + '" title="' + createLang + '"><i class="icon icon-plus-bold text-primary strong"></i></span>');

        const $viewLine = pageType == 'diff' ? $('.modified .view-lines:not(.line-delete)') : $('.view-lines');
        $viewLine.find('.view-line').eq(i).attr('class', '').addClass('view-line-' + lineNumber + ' view-line view-content-line').attr('data-line', lineNumber);
    });

    if(bugs)
    {
        const viewBugIconLeft = getViewBugIconLeft();
        for(let line in bugs)
        {
            if(line)
            {
                let lineBugs = bugs[line];
                if(pageType == 'view' && blames)
                {
                    const blame = window.getRevision(blames, line);
                    lineBugs = lineBugs.filter(bug => bug.revision == blame.revision);
                }

                const bugCount       = lineBugs.length;
                let   $viewNumberDom = pageType == 'diff' ? $('.modified .view-lines .view-line-' + line) : $('.view-lines .view-line-' + line);
                if($viewNumberDom.find('.view-line-icon .bug-count').length) $viewNumberDom.find('.view-line-icon.view-bugs').remove();
                if(bugCount) $viewNumberDom.append('<span class="view-line-icon view-bugs text-primary icon-bug-' + line + '" data-line="' + line + '" style="left: ' + viewBugIconLeft + 'px;right:auto;"><i class="icon icon-audit"></i><strong class="bug-count">' + bugCount + '</strong></span>');
            }
        }
    }
}

function getPickerItemsFromHtml(data)
{
    var options = [];
    $(data).find('option').each(function()
    {
        options.push({text: $(this).text(), value: $(this).val()});
    });
    return options;
}

function changeProduct()
{
    const productID = $('[name=product]').val();
    loadProductBranches(productID);
    link = $.createLink('repo', 'ajaxGetExecutions', 'productID=' + productID);
    $.getJSON(link, function(data)
    {
        $('#execution').picker({items: data, name: 'execution'})
    })
    moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=0&rootModuleID=0&returnType=html');
    $.getJSON(moduleLink, function(data)
    {
        $('#module').picker({items: data.items, name: 'module', required: true})
    })
}

function loadProductBranches(productID)
{
    $.getJSON($.createLink('branch', 'ajaxGetBranches', "productID=" + productID + '&oldBranch=0&browseType=active'), function(data)
    {
        if(data.length > 0)
        {
            $('#product').css('width', '70%');
            $('#branch').css('width', '30%');
            $('#branch').show();
            $('#branch').picker({items: data, name: 'branch', onChange: loadBranch});
            return;
        }
        $('#product').css('width', '100%');
        $('#branch').hide();
        $('#branch').picker({items: [], name: 'branch', onChange: loadBranch});
    });
}

function loadBranch(branch)
{
    productID = $('#product').zui('picker').$.value;
    link = $.createLink('repo', 'ajaxGetExecutions', 'productID=' + productID + '&branch=' + branch);
    $.getJSON(link, function(data)
    {
        $('#execution').picker({items: data, name: 'execution'})
    })
    moduleLink = $.createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=bug&branch=' + branch + '&rootModuleID=0&returnType=html');
    $.getJSON(moduleLink, function(data)
    {
        $('#module').picker({items: data.items, name: 'module', required: true})
    })
}

window.hiddenForm = function()
{
    var widgetid = $('#bugForm').parent().parent().parent().attr('widgetid');
    if(!widgetid) return;

    var line = parseInt(widgetid.substr(7));

    $('#bugForm').parent().parent().parent().remove();
    editor.changeViewZones(function (changeAccessor) {
        changeAccessor.removeZone(viewZoneId);
    });

    editor.revealLine(line);
}

/* remove a function */
function loadModuleRelated(){}

var $bugForm = $('#bugForm');
$(document).ready(function()
{
    var $bugFormRow = $('<div class="action-row with-action-row"><div class="action-cell"></div></div>');
    $bugFormRow.find('.action-cell').append($bugForm);

    $(document).on('click', '.bugDelete', function(e)
    {
        var $bug = $(this).closest('.panel-bug');
        if(!$bug.length) return;

        if(confirm(confirmDelete))
        {
            var bugID = $bug.data('bug');
            var link  = $.createLink('repo', 'deleteBug', 'bugID=' + bugID + '&confirm=yes');
            $.get(link, function(data)
            {
                if(data == 'deleted')
                {
                    var widgetid = $bug.parent().attr('widgetid');
                    var line     = widgetid.substr(7);

                    for(var index in bugs[line])
                    {
                        if(bugs[line][index].id == bugID)
                        {
                            bugs[line].splice(index, 1);
                        }
                    };

                    /* Refesh bug count. */
                    var $lineNumberDom = pageType == 'diff' ? $('.modified .margin-view-overlays .line-number-' + line) : $('.margin-view-overlays .line-number-' + line);
                    if(bugs[line].length)
                    {
                        $lineNumberDom.find('.bug-view .bug-count').text(bugs[line].length);
                        $('.repoCode .bug-view').css('width', $lineNumberDom.find('.line-numbers').width() - 5);
                    }
                    else
                    {
                        delete bugs[line]
                        $lineNumberDom.find('.bug-view').remove();
                    }
                    $bug.remove();

                    /* Refesh bug zone. */
                    removeBugZone();
                }
            });
        }

        e.stopPropagation();
        return false;
    }).on('click', '.addComment', function()
    {
        $(this).closest('.panel-bug').addClass('show-form').find('.commentForm textarea').focus();
    }).on('click', '.commentCancel', function()
    {
        $(this).closest('.panel-bug').removeClass('show-form');
    }).off('submit').on('submit', '.commentForm', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(json)
            {
                var $panelBug = $form.closest('.panel-bug');
                $form.find('textarea').val('');
                $panelBug.removeClass('show-form');

                var comment  = $.parseJSON(json);
                var widgetid = $(document).find("[widgetid^='bugLine']").attr('widgetid');
                var line     = parseInt(widgetid.substr(7));
                if(pageType == 'diff') line = diffContent.line.new[line - 1];
                bugs[line].forEach(function(bug, index)
                {
                    if(bug.id == comment.bugID)
                    {
                        if(!bugs[line][index].comments) bugs[line][index].comments = [];
                        bugs[line][index].comments.push(comment);
                    }
                })
                createComment(comment, $panelBug.data('bug'));
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.comment.value)
                {
                    alert(commentError);
                    return false;
                }
            }
        });
        return false;
    }).on('click', '.commentEdit', function()
    {
        var $comment = $(this).closest('.comment');

        if($comment.hasClass('show-form'))
        {
            $comment.removeClass('show-form');
            return;
        }
        $comment.addClass('show-form').find('textarea').val($comment.find('.comment-content').text()).focus();
    }).on('click', '.commentEditCancel', function()
    {
        $(this).closest('.comment').removeClass('show-form');
    }).off('submit').on('submit', '.comment-edit-form', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(html)
            {
                var $comment = $form.closest('.comment');
                $comment.find('.comment-content').html(html);
                $comment.removeClass('show-form');
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.commentText.value)
                {
                    alert(contentError);
                    return false;
                }
            }
        });
        return false;
    }).on('click', '.commentDelete', function()
    {
        var $container = $(this).closest('.commentContainer');
        if(!$container.length) return;

        if(confirm(confirmDeleteComment))
        {
            var commentID = $container.data('comment');
            var link      = $.createLink('repo', 'deleteComment', 'commentID=' + commentID + '&confirm=yes');

            $.get(link, function(data)
            {
                if(data == 'deleted')
                {
                    var $commentRow = $container.closest('.comment-row');
                    if($commentRow.find('.bugContainer, .commentContainer').length === 1)
                    {
                        $commentRow.removeClass('show').prev('tr').removeClass('commented');
                    }
                    $container.remove();
                }
            });
        }
        return false;
    });

    $(document).off('submit').on('submit', '#bugForm', function()
    {
        const form = new FormData($('#bugForm')[0]);
        $.ajaxSubmit(
        {
            url: $('#bugForm').attr('action'),
            data: form,
            onSuccess: function(json)
            {
                if(!bugs[json.line]) bugs[json.line] = [];
                bugs[json.line].push(json);

                $('#bugForm').parent().parent().parent().remove();
                editor.changeViewZones(function (changeAccessor) {
                    changeAccessor.removeZone(viewZoneId);
                });

                if(json.line) showBugs(json.line);
            },
            onFail: function(data)
            {
                const name = Object.keys(data.message)[0];
                zui.Modal.alert(data.message[name][0]);
            }
        });
        return false;
    }).on('change', 'input[name="begin"]', function()
    {
        var begin = parseInt($(this).val());
        var $end  = $('#end');
        $end.attr('min', begin);
        if(parseInt($end.val()) < parseInt(begin)) $('#end').val(begin);
        $('#assignedTo').picker('setValue', blamePairs[begin]);
    });

    $(document).on('click', function()
    {
        $('.highlight').removeClass('highlight');
    });
});

/**
 * Add view zone in editor.
 *
 * @param  object $overlayDom
 * @param  int    $line
 * @param  int    $height
 * @param  string $type
 * @param  bool   $removeOldZone
 *
 * @access public
 * @return int
 */
function addViewZone(overlayDom, line, height, type, removeOldZone = true)
{
    var zoneID = type == 'bugForm' ? viewZoneId : bugViewZoneId;
    if(removeOldZone && zoneID)
    {
        $('#bugForm').parent().parent().parent().remove();
        editor.changeViewZones(function (changeAccessor) {
            changeAccessor.removeZone(zoneID);
        });
    }

    editor.changeViewZones(function(changeAccessor)
    {
        var domNode = document.createElement('div');
        zoneId      = changeAccessor.addZone(
        {
            afterLineNumber: line,
            heightInPx: height,
            domNode: domNode,
            onDomNodeTop: (top) => {
                overlayDom.style.top = `${top}px`;
            },
            onComputedHeight: (height) => {
                overlayDom.style.height = `${height}px`;
            }
        });
    });

    /* Add an overlay widget. */
    var overlayWidget = {
        getId: () => {
            return type + line;
        },
        getDomNode: () => {
            if(type == 'bugForm')
            {
                $(overlayDom).find('#product').picker({items: products, name: 'product', onChange: changeProduct});
                $(overlayDom).find('#branch').picker({items: branches, name: 'branch', onChange: loadBranch});
                $(overlayDom).find('#execution').picker({items: executions, name: 'execution'});
                $(overlayDom).find('#module').picker({items: modules, name: 'module', required: true});
                setTimeout(() =>
                {
                    $('#product').picker('setValue', repoProduct)
                    $('#module').picker('setValue', repoModule);
                }, 300);
                $(overlayDom).find('#repoType').picker({items: typeList, name: 'repoType'});
                $(overlayDom).find('#assignedTo').picker({items: userList, name: 'assignedTo'});
                $(overlayDom).css('width', '495px');
                $(overlayDom).css('border', '1px solid #bbb');
            }
            else
            {
                $(overlayDom).css('width', editorWidth - 50 + 'px');
                $(overlayDom).css('overflow-y', 'scroll');
            }

            $(overlayDom).css('left', zoneLeft);

            return overlayDom;
        },
        getPosition: function()
        {
            return null;
        }
    };

    editor.addOverlayWidget(overlayWidget);
    return zoneId;
}

/**
 * Show bugs.
 *
 * @param  int   $line
 * @access public
 * @return void
 */
function showBugs(line)
{
    var realLine = diffContent.line !== undefined ? diffContent.line.new[line - 1] : line;
    if(!bugs[realLine]) return false;

    var bugIDs = [];
    $.each(bugs[realLine], function(i, bug)
    {
        bugIDs.push(bug.id);
    });
    showBugsBlock(bugIDs);
}

/**
 * Remove bug zone.
 *
 * @access public
 * @return void
 */
function removeBugZone()
{
    $(document).find("[widgetid^='bugLine']").remove();
    editor.changeViewZones(function (changeAccessor) {
        changeAccessor.removeZone(bugViewZoneId);
    });
}

/**
 * Create comment.
 *
 * @param  object $comment
 * @param  object $comments
 * @access public
 * @return object
 */
function createComment(comment, $comments)
{
    var $commentCell = $('#commentCell');
    var $comment     = $commentCell.clone().removeClass('hide').attr('id', 'comment-' + comment.id).attr('data-comment', comment.id);
    $comment.find('.realname').text(comment.realname);
    $comment.find('.comment-content').text(comment.comment);
    $comment.find('.date').text(comment.date);
    $comment.find('.edit').toggle(comment.edit);
    $comment.find('.comment-edit-form').attr('action', $.createLink('repo', 'editComment', 'commentID=' + comment.id));

    if(comment.user.avatar)
    {
        $comment.find('.avatar').removeClass('has-text').addClass('has-img');
        $comment.find('.avatar span').remove();
        $comment.find('.avatar').html('<img src="' + comment.user.avatar + '"/>');
    }
    else
    {
        $comment.find('.avatar').removeClass('has-img').addClass('has-text');
        $comment.find('.avatar img').remove();
        var name = comment.user.name ? comment.user.name : (comment.user.realname ? comment.user.realname : comment.user.account);
        $comment.find('.avatar').html('<span class="text text-len-' + name.replace(/[^\x00-\xff]/g, "00").length + '">' + name.toUpperCase().slice(0,1) + '</span>');
    }

    if($comments)
    {
        if(typeof $comments !== 'object') $comments = $('#bug-' + $comments + ' .comments');
        ($comments.hasClass('comments') ? $comments : $comments.find('.comments')).append($comment);
    }

    return $comment;
};

/**
 * Show bug form.
 *
 * @param  int    line
 * @access public
 * @return void
 */
function showBugForm(line)
{
    var realLine = diffContent.line !== undefined ? diffContent.line.new[line - 1] : line;
    let revision = bugRevision;
    if(blames)
    {
        let blame = blames[realLine];
        if(blame)
        {
            var p_line = parseInt(line);
            while(!blame.revision)
            {
                p_line--;
                blame = blames[p_line];
            }
            if(blame) revision = blame.revision;
        }
    }

    $bugForm.find('input[name="revision"]').val(revision);
    $bugForm.find('input[name="title"]').val('');
    $bugForm.find('input[name="begin"]').val(realLine);
    $bugForm.find('input[name="end"]').attr('min', realLine).val(realLine);
    $bugForm.find('select#assignedTo').val(blamePairs[realLine]);
    $bugForm.find('select#assignedTo').trigger("chosen:updated");
    $bugForm.show();

    /* Add a zone to review code. */
    var overlayDom = document.createElement('div');
    var container = '<div id="bugContainer" class="panel panel-form size-lg pb-0 mb-0"><div class="panel-heading"><div class="panel-title text-lg">' + addIssueTip + '</div></div><div class="pt-4 px-5">' + $bugForm.parent().html() + '</form></div></div>';
    overlayDom.innerHTML = container;
    $(overlayDom).find('input[name="begin"]').val(realLine);
    $(overlayDom).find('input[name="end"]').attr('min', realLine).val(realLine);
    viewZoneId = addViewZone(overlayDom, line, 550, 'bugForm');

    // KindEditor.remove('#commentText');
    $('.ke-container').remove();
    // $('#commentText').kindeditor();
}
