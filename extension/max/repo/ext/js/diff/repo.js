$(document).ready(function()
{
    var $diffCode = $('.diff');
    var hidePreview;
    var $bugsPreview     = $('#bugsPreview');
    var $bugsPreviewMenu = $('#bugsPreview').children('.dropdown-menu');
    var $rows            = $diffCode.find('tr');
    var rowTip           = $('#rowTip').html();
    var lastLine;
    $rows.each(function()
    {
        var $row = $(this);
        if(!$row.hasClass('empty'))
        {
            $row.children('th').first().prepend("<div class='comment-btn diff'><span class='icon-wrapper'><i class='icon-plus'></i></span></div>");
            $row.children('td').first().append(rowTip);

            if($row.data('type') != 'all')
            {
                var lineID  = $row.data('line');
                var fileSrc = $row.data('src');

                $rightTh = $row.children('th').first().next();
                if(arrange == 'appose') $rightTh = $rightTh.next();
                $rightTh.prepend("<div class='diff-btn diff' onclick=showCommitInfo(this)><i class='icon-info-sign'></i></div>");
            }
        }

        if(lastLine && !$row.data('line'))
        {
            $row.attr('data-line', lastLine);
        }
        else
        {
            lastLine = $row.data('line');
        }
    }).hover(function()
    {
        var $this = $(this);
        if($this.hasClass('empty')) return;
        $this.addClass("over");
    },
    function()
    {
        $(this).removeClass("over");
    });

    var isInline = $.cookie('arrange') == 'inline';
    var $bugFormRow = $('<tr class="action-row"><th></th>' + (isInline ? '<th></th><td class="action-cell"></td>' : '<td colspan="3" class="action-cell"></td>') + '</tr>');
    var $bugForm = $('#bugForm');
    var $commentCell = $('#commentCell');
    var $bugPanel = $('#bugPanel');
    $bugFormRow.find('td').append($bugForm.removeClass('hide'));

    var highlight = function($e)
    {
        $('.highlight').removeClass('highlight');
        $e.addClass('highlight');
    };

    var createComment = function(comment, $comments)
    {
        var $comment = $commentCell.clone()
            .removeClass('hide')
            .attr('id', 'comment-' + comment.id)
            .attr('data-comment', comment.id);
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
            $comment.find('.avatar').html('<span class="text text-len-' + name.replace(/[^\x00-\xff]/g, "00").length + '">' + name + '</span>');
        }

        if($comments)
        {
            if(typeof $comments !== 'object') $comments = $('#bug-' + $comments + ' .comments');
            ($comments.hasClass('comments') ? $comments : $comments.find('.comments')).append($comment);
        }

        return $comment;
    };

    var createBug = function(bug, line, $commentRow, show)
    {
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

        if(!line && bug.line) line = bug.line;
        if(line)
        {
            if(!$commentRow)
            {
                var $row = $rows.filter('[data-line="' + line + '"][data-src="' + bug.file + '"]').last();
                $commentRow = $row.next('tr');
                if(!$commentRow.hasClass('comment-row'))
                {
                    $commentRow = $('<tr class="comment-row"><th class="w-num"></th>' + (isInline ? '<th class="w-num"></th><td class="comment-cell"><div class="comment-list"></div></td>' : '<td colspan="3" class="comment-cell"><div class="comment-list"></div></td>') + '</tr>');
                    $row.addClass('commented').after($commentRow);
                }
            }
            ($commentRow.hasClass('comment-list') ? $commentRow : $commentRow.find('.comment-list')).append($bug);

            if(show && $commentRow.hasClass('comment-row')) $commentRow.addClass('show');
        }

        $bug.find('.iframe').initIframeModal();
        return $bug;
    };

    var toggleComment = function($row, show)
    {
        var $commentRow;
        if($row.hasClass('comment-row'))
        {
            $commentRow = $row;
            $row = $commentRow.prev('tr');
            if($row.hasClass('action-row'))
            {
                $row = $row.prev('tr');
            }
        }
        else
        {
            $commentRow = $row.next('tr');
            if($commentRow.hasClass('action-row'))
            {
                $commentRow = $commentRow.next('tr');
            }
        }
        if(show === undefined)
        {
            show = !$row.hasClass('open');
        }
        if($row.hasClass('commented') && $commentRow.hasClass('comment-row'))
        {
            $commentRow.toggleClass('show', show);
            $row.toggleClass('open', show);
        }
    };

    $diffCode.on('click', '.comment-btn', function(e)
    {
        $rows.removeClass('selected');
        var $row  = $(this).closest('tr');
        if($diffCode.hasClass('with-action-row') && $row.hasClass('with-action-row'))
        {
            $diffCode.removeClass('with-action-row');
        }
        else
        {
            $diffCode.addClass('with-action-row');
            var line     = $row.data('line');
            var fileName = $row.data('src');
            if(!$row.hasClass('with-action-row'))
            {
                $rows.removeClass('with-action-row')
                $row.addClass('with-action-row');

                $bugForm.find('input[name="entry"]').val(fileName);
                $bugForm.find('input[name="begin"]').val(line);
                $bugForm.find('input[name="end"]').attr('min', line).val(line);
                $bugForm.find('select#assignedTo').val(blamePairs[line]);
                $bugForm.find('select#assignedTo').trigger("chosen:updated");

                $row.after($bugFormRow);

                KindEditor.remove('#commentText');
                $('#commentText').kindeditor();

                var getCommiterLink = createLink('repo', 'ajaxgetcommitter', 'repoID=' + repoID + "&entry=" + file + "&revision=" + revision + "&line=" + line);
                var connector = getCommiterLink.indexOf('&') >= 0 ? '&' : '?';
                getCommiterLink = getCommiterLink + connector + 'entry=' + file;
                $.ajax({url: getCommiterLink}).done(function(responseText)
                {
                    $bugForm.find('#assignedTo').val(responseText).trigger("chosen:updated");
                });
            }
            highlight($bugForm);
            $bugForm.find('input[name="title"]').focus();
            $row.addClass('selected');
        }
        e.stopPropagation();
    }).on('click', '.bugCancel', function()
    {
        $rows.removeClass('selected');
        $diffCode.removeClass('with-action-row');
    }).on('click', '.bugEdit,.view-bug', function(e)
    {
        if(!isonlybody) return false;
    }).on('click', '.bugDelete', function(e)
    {
        var $bug = $(this).closest('.panel-bug');
        if(!$bug.length) return;

        if(confirm(confirmDelete))
        {
            var link  = createLink('repo', 'deleteBug', 'bugID=' + $bug.data('bug') + '&confirm=yes');
            $.get(link, function(data)
            {
                if(data == 'deleted')
                {
                    var $commentRow = $bug.closest('.comment-row');
                    if($commentRow.find('.panel-bug').length === 1)
                    {
                        $commentRow.removeClass('show').prev('tr').removeClass('commented');
                    }
                    $bug.remove();
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
                createComment($.parseJSON(json), $panelBug.data('bug'));
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
    }).on('click', 'tr.commented', function()
    {
        toggleComment($(this));
    }).on('click', '.panel-bug > .panel-heading', function()
    {
        $(this).closest('.panel-bug').toggleClass('show');
    }).on('mouseenter', 'tr.commented td .preview-icon', function(e)
    {
        var $cell = $(this).closest('td');
        var $row = $cell.closest('tr');
        var $commentRow = $row.next('tr');

        var $bugs = $commentRow.find('.panel-bug'), line = '?';
        $bugsPreviewMenu.children('li:not(.dropdown-header)').remove();
        $bugsPreviewMenu.find('.bug-count').text($bugs.length);
        $bugsPreviewMenu.find('.comment-count').text($commentRow.find('.comment').length);
        $bugs.each(function()
        {
            var bug = $(this).data('data');
            line = bug.line;
            $bugsPreviewMenu.append('<li><a href="javascript:;" data-id="#bug-' + bug.id + '"><small class="text-muted">#' + bug.id + '</small> ' + bug.title + '</a></li>');
        });
        $bugsPreviewMenu.find('.code-line').text(line);

        $bugsPreview.prependTo($cell);
        clearTimeout(hidePreview);
        $bugsPreviewMenu.css({top: 0-$bugsPreviewMenu.outerHeight(), left: Math.max(0, e.offsetX-$bugsPreviewMenu.outerWidth())}).addClass('show');
        setTimeout(function(){$bugsPreviewMenu.addClass('in');}, 50);
    }).on('mouseleave', 'tr.commented td', function()
    {
        $bugsPreviewMenu.removeClass('in');
        hidePreview = setTimeout(function(){$bugsPreviewMenu.removeClass('show');}, 200);
    });

    $bugsPreviewMenu.on('click', 'li', function(e)
    {
        var $bug = $($(this).find('a').data('id'));
        if($bug.length)
        {
            $bug.addClass('show');
            toggleComment($bug.closest('tr.comment-row'), true);
            highlight($bug);

            $bugsPreviewMenu.removeClass('in');
            hidePreview = setTimeout(function(){$bugsPreviewMenu.removeClass('show');}, 200);
        }
        e.stopPropagation();
    });

    $bugForm.submit(function()
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

                createBug(json, null, null, 3);
                $diffCode.removeClass('with-action-row');
                $diffCode.find('tr.with-action-row.selected').removeClass('selected');
                $bugForm.find('#title').val('');
                KindEditor.html('#commentText', '');
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
        var $end = $bugForm.find('input[name="end"]').attr('min', begin);
        if(parseInt($end.val()) < parseInt(begin))
        {
            $end.val(begin);
        }
    });

    if(bugs)
    {
        var lineBugs, bugsCount, i;
        for(var line in bugs)
        {
            if(line)
            {
                lineBugs = bugs[line];
                bugsCount = lineBugs.length;

                for(i = 0; i < bugsCount; i++)
                {
                    createBug(lineBugs[i], line);
                }
            }
        }
    }

    setTimeout(anchor, 200);

    $(document).on('click', function()
    {
        $('.highlight').removeClass('highlight');
    });

    $(document).mouseup(function(e)
    {
        var $box = $('#commit-box');
        if(!$box.is(e.target) && $box.has(e.target).length === 0)
        {
            $('#commit-box').remove();
        }
    });

    function anchor()
    {
        var hash  = window.location.hash;
        if(hash)
        {
            var line = hash.substr(1).replace('L', '');
            var $row = $('.diff tr[data-line="' + line +'"]').first();
            if($row.length)
            {
                var anchor = $row.offset().top;

                $('body,html').animate({scrollTop:anchor - 50}, 500);

                $row.addClass('highlight');
                if($row.hasClass('commented'))
                {
                    toggleComment($row, true);
                    var $commentRow = $row.next('tr');
                    if($commentRow.hasClass('comment-row'))
                    {
                        $commentRow.addClass('highlight');
                    }
                }
            }
        }
    }

    /* Diff url locate. */
    $('.diffForm').click(function()
    {
        if($(this).hasClass('disabled')) return false;

        var oldRevision   = $('#oldRevision').val();
        var newRevision   = $('#newRevision').val();
        var isBranchOrTag = $('#isBranchOrTag').val();
        if(!oldRevision || !newRevision)
        {
            (repo.SCM != 'Subversion') ? alert(repoLang.error.needTwoVersion) : alert(repoLang.error.emptyVersion);
            return false;
        }

        if(repo.SCM == 'Subversion')
        {
            var intRe = /^\d+$/;
            if((intRe.test(oldRevision) == false && oldRevision != '^') || (intRe.test(newRevision) == false && newRevision != '^'))
            {
                alert(repoLang.error.versionError);
                return false;
            }
        }

        if(oldRevision == newRevision)
        {
            alert(repoLang.error.differentVersions);
            return false;
        }

        if(isBranchOrTag)
        {
            oldRevision = btoa(encodeURIComponent(oldRevision));
            newRevision = btoa(encodeURIComponent(newRevision));
        }

        var url = createLink('repo', 'diff', 'repoID=' + repoID + '&objectID=' + objectID + '&entry=&oldRevision=' + oldRevision + '&newRevision=' +newRevision + '&showBug=0&encoding=&isBranchOrTag=' + isBranchOrTag);

        window.location = url;
    });

    $(document).on('click', '.branch-or-tag', function()
    {
        $('.diffForm').removeClass('disabled');
        var isSource = $(this).hasClass('source');
        var version  = $(this).text();
        if(isSource)
        {
            if($('#newRevision').val() == version) $('.diffForm').addClass('disabled');
            $('#sourceSwapper .version-name').text(version);
            $('#sourceSwapper .repo-select').attr('title', version);
            $('#oldRevision').val(version);
            $('#sourceSwapper .branch-or-tag').removeClass('selected');
        }
        else
        {
            if($('#oldRevision').val() == version) $('.diffForm').addClass('disabled');
            $('#targetSwapper .version-name').text(version);
            $('#targetSwapper .repo-select').attr('title', version);
            $('#newRevision').val(version);
            $('#targetSwapper .branch-or-tag').removeClass('selected');
        }
        $(this).addClass('selected');
    });

    $(document).on('input', '.svn-version', function()
    {
        $('.diffForm').removeClass('disabled');
        var oldRevision = $('#oldRevision').val();
        var newRevision = $('#newRevision').val();
        if(oldRevision == newRevision) $('.diffForm').addClass('disabled');
    });

    $(document).on('click', '.label-exchange-git', function()
    {
        var source = $('#oldRevision').val();
        var target = $('#newRevision').val();
        if(source && target)
        {
            $('#oldRevision').val(target);
            $('#newRevision').val(source);
            $('.diffForm').click();
        }
    })

    $('.file-name').click(function()
    {
        $(this).prev().click();
    });

    /** Fix English grammar errors. */
    if($(".add-cot").length > 0)
    {
        $('.add-cot').html($('.add-cot').html().replace('1 lines', '1 line'));
        $('.delete-cot').html($('.delete-cot').html().replace('1 lines', '1 line'));
    }

    /* Disable the enter event. */
    $(document).keydown(function(event)
    {
        if(event.keyCode == 13) return false;
    });

    /** Press enter to trigger version search. */
    $("#searchSource").keydown(function(event)
    {
        if(event.keyCode == 13)
        {
            var version = $(this).val();
            if(!version) return;
            $('#sourceSwapper .version-name').text(version);
            $('#sourceSwapper .repo-select').attr('title', version);
            $('#oldRevision').val(version);
            $('#sourceSwapper .branch-or-tag').removeClass('selected');
        }
    });
    $("#searchTarget").keydown(function(event)
    {
        if(event.keyCode == 13)
        {
            var version = $(this).val();
            if(!version) return;
            $('#targetSwapper .version-name').text(version);
            $('#targetSwapper .repo-select').attr('title', version);
            $('#newRevision').val(version);
            $('#targetSwapper .branch-or-tag').removeClass('selected');
        }
    });

    if(['Git', 'Gitlab', 'Gogs', 'Gitea'].indexOf(repo.SCM)) getBranchAndTag();
});

/**
 * Change encode.
 *
 * @param  string $encoding
 * @access public
 * @return void
 */
function changeEncoding(encoding)
{
    $('#encoding').val(encoding);
    $('#encoding').parents('form').submit();
}

/**
 * Get branch and tag.
 *
 * @param  int $repoID
 * @access public
 * @return void
 */
function getBranchAndTag()
{
    if(repoID)
    {
        var link  = createLink('repo', 'ajaxGetBranchesAndTags', 'repoID=' + repoID + '&oldRevision=' + sourceRevision + '&newRevision=' + revision);
        $.get(link, function(data)
        {
            var result = $.parseJSON(data);
            $('#sourceList').empty();
            $('#targetList').empty();
            $('#sourceList').append(result.sourceHtml);
            $('#targetList').append(result.targetHtml);
        });
    }
}

/**
 * Expand and collapse code blocks.
 *
 * @param  node icon
 * @access public
 * @return void
 */
function diffToggle(icon)
{
    if($(icon).hasClass('list-toggle-open'))
    {
        $(icon).removeClass('list-toggle-open');
        $(icon).parents('.table').find('tr').hide();
        $(icon).parents('.table').find('.comment-row').addClass('hideImportant');
    }
    else
    {
        $(icon).addClass('list-toggle-open');
        $(icon).parents('.table').find('tr').show();
        $(icon).parents('.table').find('.comment-row').removeClass('hideImportant');
    }
}

/**
 * Jump to file.
 *
 * @param int  $fileKey
 * @param node $aNode
 * @access public
 * @return void
 */
function jumpFile(fileKey, aNode)
{
    $("#fileTree a").removeClass('selected');
    $(aNode).addClass('selected');
    $("#file" + fileKey).find('.list-toggle').click();
}

/**
 * Show commit info.
 *
 * @param  node   $row
 * @access public
 * @return void
 */
function showCommitInfo(row)
{
    var fileSrc = $(row).parent().parent().data('src');
    var line    = $(row).parent().parent().data('line');
    var link    = createLink('repo', 'ajaxGetCommitInfo');
    var data    = {
        repoID        : repoID,
        entry         : fileSrc,
        revision      : revision,
        sourceRevision: sourceRevision,
        line          : line,
    };

    $.post(link, data, function(html)
    {
        $(row).parent().append(html);
    })
}
