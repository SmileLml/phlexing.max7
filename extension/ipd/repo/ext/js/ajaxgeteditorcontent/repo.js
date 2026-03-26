var $bugForm = $('#bugForm');
$(document).ready(function()
{
    var $bugFormRow = $('<div class="action-row with-action-row"><div class="action-cell"></div></div>');
    $bugFormRow.find('.action-cell').append($bugForm);
    var $bugPanel     = $('#bugPanel');
    var $monacoEditor = $('.repoCode .content .monaco-scrollable-element');
    var $bugsPreview  = $('#bugsPreview');
    var hidePreview;
    var $bugsPreviewMenu = $('#bugsPreview').children('.dropdown-menu');

    var $rows = $monacoEditor.find('.content-line');
    var highlight = function($e)
    {
        $('.highlight').removeClass('highlight');
        $e.addClass('highlight');
    };

    var createBug = function(bug, line, $commentRow, show)
    {
        /* Add bug info to bugs. */
        var newBug = {
            id:    bug.id,
            line:  bug.line,
            title: bug.title
        };
        if(!bugs[bug.line]) bugs[bug.line] = [];
        bugs[bug.line].push(newBug);

        var commentCount, j;
        var $bug = $bugPanel.clone().removeClass('hide').attr('id', 'bug-' + bug.id).attr('data-bug', bug.id);
        $bug.find('.bugid').text(bug.id);
        $bug.find('.view-bug').attr('title', bug.title);
        $bug.find('.edit').toggle(bug.edit);
        $bug.find('.code-lines').text(bug.lines);
        $bug.find('.delete').toggle(bug.delete);
        $bug.find('input[name="objectID"]').val(bug.id);
        $bug.find('a.view-bug').attr('href', createLink('bug', 'view', 'bugID=' + bug.id + '&from=repo', '', true));
        $bug.find('a.bugEdit').attr('href', createLink('bug', 'edit', 'bugID=' + bug.id + '&from=repo', '', true));
        $bug.data('data', bug);
        $bug.toggleClass('show', show > 1);
        if(show > 2) highlight($bug);

        if(bug.comments)
        {
            commentCount = bug.comments.length;
            $bugComments = $bug.find('.comments');
            for(j = 0; j < commentCount; j++)
            {
                createComment(bug.comments[j], $bugComments);
            }
        }
        $bug.find('.iframe').initIframeModal();

        /* Remove old zone. */
        removeBugZone();

        /* Add a zone to show bug. */
        var overlayDom = document.createElement('div');
        var bugLine    = parseInt(bug.line);
        if(pageType == 'diff') bugLine = jQuery.inArray(bugLine, diffContent.line.new) + 1;
        $bug.appendTo($(overlayDom));
        bugViewZoneId = addViewZone(overlayDom, bugLine, 125, 'bugLine');
        editor.revealLine(bugLine);

        return $bug;
    };

    $(document).on('click', '.bugDelete', function(e)
    {
        var $bug = $(this).closest('.panel-bug');
        if(!$bug.length) return;

        if(confirm(confirmDelete))
        {
            var bugID = $bug.data('bug');
            var link  = createLink('repo', 'deleteBug', 'bugID=' + bugID + '&confirm=yes');
            $.get(link, function(data)
            {
                if(data == 'deleted')
                {
                    var $commentRow = $bug.closest('.comment-row');
                    var bugNum      = parseInt($commentRow.prev('tr').find('.bug-num').text());
                    var widgetid    = $bug.parent().attr('widgetid');
                    var line        = widgetid.substr(7);

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
    }).on('submit', '.commentForm', function()
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
    }).on('submit', '.comment-edit-form', function()
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
            var link      = createLink('repo', 'deleteComment', 'commentID=' + commentID + '&confirm=yes');

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

    $(document).on('submit', '#bugForm', function()
    {
        $(this).ajaxSubmit(
        {
            success:function(json)
            {
                json = $.parseJSON(json);
                if(json.result == 'fail')
                {
                   alert(json.message);
                   return false;
                }

                if(!bugs[json.line]) bugs[json.line] = [];
                bugs[json.line].push(json);

                $('#bugForm').parent().remove();
                editor.changeViewZones(function (changeAccessor) {
                    changeAccessor.removeZone(viewZoneId);
                });
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.product.value)
                {
                    alert(productError);
                    return false;
                }
                if(!form.title.value)
                {
                    alert(titleError);
                    $bugForm.find('input[name="title"]').focus();
                    return false;
                }
            }
        });
        return false;
    }).on('change', 'input[name="begin"]', function()
    {
        var begin = $(this).val();
        var $end  = $bugForm.find('input[name="end"]').attr('min', begin);
        if(parseInt($end.val()) < parseInt(begin)) $end.val(begin);
        $bugForm.find('select#assignedTo').val(blamePairs[begin]);
        $bugForm.find('select#assignedTo').trigger("chosen:updated");
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
        $('#bugForm').parent().remove();
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
                /* Remove old chosen div and init chosen.*/
                $(overlayDom).find('div[id$=chosen]').each(function(index, $chosenDiv)
                {
                    $chosenDiv.remove();
                });
                $(overlayDom).find('.chosen').each(function(index, chosenSelect)
                {
                    $(chosenSelect).chosen();
                });

                $(overlayDom).css('width', '495px');
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
    var realLine = diffContent ? diffContent.line.new[line - 1] : line;
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
    $comment.find('.comment-edit-form').attr('action', createLink('repo', 'editComment', 'commentID=' + comment.id));

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
    var realLine = diffContent ? diffContent.line.new[line - 1] : line;

    $bugForm.find('input[name="title"]').val('');
    $bugForm.find('input[name="begin"]').val(realLine);
    $bugForm.find('input[name="end"]').attr('min', realLine).val(realLine);
    $bugForm.find('select#assignedTo').val(blamePairs[realLine]);
    $bugForm.find('select#assignedTo').trigger("chosen:updated");
    $bugForm.show();

    /* Add a zone to review code. */
    var overlayDom = document.createElement('div');
    overlayDom.innerHTML = $bugForm.parent().html();
    $(overlayDom).find('input[name="begin"]').val(realLine);
    $(overlayDom).find('input[name="end"]').attr('min', realLine).val(realLine);
    viewZoneId = addViewZone(overlayDom, line, 545, 'bugForm');

    KindEditor.remove('#commentText');
    $('.ke-container').remove();
    $('#commentText').kindeditor();
}
