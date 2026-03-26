$(document).ready(function()
{
    var $bugFormRow = $('<tr class="action-row"><th></th><td class="action-cell"></td></tr>');
    var $bugForm = $('#bugForm');
    $bugFormRow.find('td').append($bugForm.removeClass('hide'));
    var $bugPanel = $('#bugPanel');
    var $commentCell = $('#commentCell');
    var $pre = $('.repoCode .content pre');
    var $bugsPreview = $('#bugsPreview');
    var hidePreview;
    var $bugsPreviewMenu = $('#bugsPreview').children('.dropdown-menu');

    var $rows = $pre.find('tr');
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
        $bug.find('a.view-bug').attr('href', createLink('bug', 'view', 'bugID=' + bug.id, '', true));
        $bug.find('a.bugEdit').attr('href', createLink('bug', 'edit', 'bugID=' + bug.id, '', true));
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
            var $row = $rows.filter('[data-line="' + line + '"]').first();
            var bugNum = parseInt($row.find('span .bug-num').text()) + 1;
            $row.find('span .bug-num').text(bugNum);
            if(!$commentRow)
            {
                $commentRow = $row.next('tr');
                if(!$commentRow.hasClass('comment-row'))
                {
                    $commentRow = $('<tr class="comment-row"><th class="w-num"></th><td class="comment-cell"><div class="comment-list"></div></td></tr>');
                    $row.after($commentRow);
                }
            }
            $row.addClass('commented');

            var bugWidth = 31 + bugNum.toString().length * 7;
            var minWidth = parseInt($('.repoCode tr .icon-wrapper').css('min-width'));
            if(bugWidth > minWidth) $('.repoCode tr .icon-wrapper').css('min-width', bugWidth + 'px');

            var bugThWidth = bugWidth + 22;
            minWidth = parseInt($('.repoCode pre > table > tbody tr > th').css('width'));
            if(bugThWidth > minWidth) $('.repoCode pre > table > tbody > tr > th').css('width', bugThWidth + 'px');
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

    $rows.hover(function()
    {
        $(this).addClass("over");
        $(this).find(".icon-wrapper .icon-bug").addClass('icon-chat').removeClass('icon-bug');
        $(this).find(".label-badge").hide();
        $(this).find('.row-tip .icon-chat').hide();
    },
    function()
    {
        $(this).removeClass("over");
        $(this).find(".icon-wrapper .icon-chat").addClass('icon-bug').removeClass('icon-chat');
        $(this).find(".label-badge").show();
    });

    $pre.on('click', '.comment-btn', function(e)
    {
        $rows.removeClass('selected');
        var $row  = $(this).closest('tr');
        if($pre.hasClass('with-action-row') && $row.hasClass('with-action-row'))
        {
            $pre.removeClass('with-action-row');
        }
        else
        {
            $pre.addClass('with-action-row');
            var line = $row.data('line');
            if(!$row.hasClass('with-action-row'))
            {
                $rows.removeClass('with-action-row')
                $row.addClass('with-action-row');

                $bugForm.find('input[name="begin"]').val(line);
                $bugForm.find('input[name="end"]').attr('min', line).val(line);
                $bugForm.find('select#assignedTo').val(blamePairs[line]);
                $bugForm.find('select#assignedTo').trigger("chosen:updated");

                var $nextRow = $row.next('tr');
                ($nextRow.hasClass('comment-row') ? $nextRow : $row).after($bugFormRow);

                var getCommiterLink = createLink('repo', 'ajaxgetcommitter', 'repoID=' + repoID + "&entry=&revision=" + revision + "&line=" + line);
                var connector = getCommiterLink.indexOf('&') >= 0 ? '&' : '?';
                getCommiterLink = getCommiterLink + connector + 'entry=' + file;
                $.ajax({url: getCommiterLink}).done(function(responseText)
                {
                    $bugForm.find('#assignedTo').val(responseText).trigger("chosen:updated");
                });
            }
            $row.addClass('selected');

            highlight($bugForm);
            $bugForm.find('input[name="title"]').focus();
        }

        e.stopPropagation();
    }).on('click', '.bugEdit,.view-bug', function(e)
    {
        if(!isonlybody) return false;
    }).on('click', '.bugCancel', function()
    {
        $rows.removeClass('selected');
        $pre.removeClass('with-action-row');
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
                    var bugNum      = parseInt($commentRow.prev('tr').find('.bug-num').text());
                    $commentRow.prev('tr').find('.bug-num').text(bugNum > 1 ? bugNum -1 : 0);

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
                $pre.removeClass('with-action-row');
                $pre.find('tr.with-action-row.selected').removeClass('selected');
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
        if(parseInt($end.val()) < parseInt(begin)) $end.val(begin);
        $bugForm.find('select#assignedTo').val(blamePairs[begin]);
        $bugForm.find('select#assignedTo').trigger("chosen:updated");
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

    function anchor()
    {
        var hash  = window.location.hash;
        if(hash)
        {
            var $row = $(hash).closest('tr');
            if($row.length)
            {
                var anchor = $row.offset().top;

                $('body,html').animate({scrollTop:anchor - 50}, 500);

                $row.addClass('highlight');
                if($row.hasClass('commented'))
                {
                    var $commentRow = $row.next('tr');
                    if($commentRow.hasClass('comment-row'))
                    {
                        toggleComment($row, true);
                        $commentRow.addClass('highlight').find('.panel-bug').first().addClass('show');
                    }
                }
            }
        }
    }
});
