<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<?php js::set('repoLang', $lang->repo);?>
<?php js::set('bugIDList', $bugIDList);?>
<?php js::set('reviewComments', $comments);?>
<?php js::set('contentError', $lang->repo->error->commentText);?>
<?php js::import($jsRoot . '/zui/tabs/tabs.min.js');?>
<div id='reviewBugs'>
  <div class="modal-header detail-title tabs-navbar">
    <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
    <div class="btn close close-bugs pull-right"><i class="icon icon-close"></i></div>
    <div class="btn btn-right pull-right"><i class="icon icon-chevron-right"></i></div>
    <ul class="nav nav-tabs bug-tabs">
      <?php $index = 0;?>
      <?php foreach($bugs as $bug):?>
        <li <?php if($index ==0) echo 'class="active"';?>>
        <a href="###" data-target="#repoViewBug<?php echo $bug->id;?>" data-toggle="tab"><?php echo $bug->title;?></a>
      </li>
      <?php $index++;?>
      <?php endforeach;?>
    </ul>
  </div>
  <div class='review-bug-list'>
    <div id="bugAccordion" class="tab-content">
      <?php $index = 0;?>
      <?php foreach($bugs as $bug):?>
      <div class="tab-pane fade bug-view <?php echo $index == 0 ? 'active in' : '';?>" id="repoViewBug<?php echo $bug->id;?>">
        <div class="with-padding nobr bug-user">
          <span><?php echo html::smallAvatar(zget($users, $bug->openedBy));?></span>
          <span class='strong'><?php echo isset($users[$bug->openedBy]) ? $users[$bug->openedBy]->realname : $bug->openedBy;?></span>
          <div class="text-muted"><?php echo sprintf($lang->repo->dateTmpl, $bug->openedDate);?></div>
        </div>
        <div class="with-padding">
          <span><?php echo $lang->repo->issueTitle;?></span>
          <span class='strong'><?php echo $bug->title;?></span>
        </div>
        <?php if($bug->steps):?>
        <div class="with-padding">
          <span><?php echo $lang->repo->issueDesc;?></span>
          <div class='strong bug-detail'><?php echo $bug->steps;?></div>
          <div class='detail-unfold text-center'><?php echo $lang->repo->expand . ' ';?><i class='icon icon-caret-down'></i></div>
        </div>
        <?php endif;?>
        <div class="with-padding">
          <div class='tabs'>
            <ul class='nav nav-tabs'>
              <li class='active'><a href='#legendComment<?php echo $bug->id;?>' data-toggle='tab' class='bug-comment<?php echo $bug->id;?>'><?php echo $lang->repo->comment;?>(<span>0</span>)</a></li>
            </ul>
            <?php if(common::hasPriv('repo', 'addComment')):?>
            <div class='tab-content'>
              <div class='tab-pane active' id='legendComment<?php echo $bug->id;?>'>
                <p>
                  <form class='commentForm not-watch' data-bugID="<?php echo $bug->id;?>" method='post' action='<?php echo $commentUrl?>' id='commentForm'>
                    <textarea name='comment' class='commentText form-control mgb-10' spellcheck='false' placeholder='<?php echo $lang->repo->notice->commentContent?>'></textarea>
                    <input class='commentSubmit btn btn-sm btn-primary pull-right' type='submit' value='<?php echo $lang->repo->submit?>'>
                    <input type='hidden' name='objectID' value='<?php echo $bug->id;?>'>
                    <div class='optional'></div>
                  </form>
                </p>
              </div>
            </div>
            <?php endif;?>
          </div>
        </div>
        <div class="with-padding hidden comment-list">
          <p class='detail-title bug-comment<?php echo $bug->id;?>'><span>0</span><?php echo $lang->repo->commentNum;?></p>
          <div id="reviewComment<?php echo $bug->id;?>"></div>
        </div>
      </div>
      <?php $index++;?>
      <?php endforeach;?>
    </div>
  </div>
</div>
<div id="commentTemp" class="hidden">
  <div class='comment-block'>
    <div class='comment-user'>
      <span class="avatar %imgClass% avatar-circle avatar-sm pull-left">%avatar%</span>
      <strong class="realname">%realname%</strong>
    </div>
    <div class='comment-desc' id="comment-%commentID%">
      <div class='comment-content' title="%comment%">%comment%</div>
      <div class="comment-form-div hidden">
        <form method="post" class="comment-edit-form not-watch" action="<?php echo $this->createLink('repo', 'editComment', 'commentID=%commentID%');?>">
          <textarea name="commentText" class="commentInput form-control mgb-10">%comment%</textarea>
          <button type="submit" class="btn btn-sm btn-primary commentEditSubmit"><?php echo $lang->repo->submit;?></button>
          <button type="button" class="btn btn-sm commentEditCancel"><?php echo $lang->cancel;?></button>
        </form>
      </div>
    </div>
    <div>
      <span class='text-muted comment-date'>
        <span class='date'>%date%</span>
      </span>
      <?php if(common::hasPriv('repo', 'editComment')):?>
      <a href='javascript:;' class='edit commentEdit pull-right text-muted' data-id="%commentID%">
        <i class='icon icon-edit'></i>
      </a>
      <?php endif;?>
    </div>
  </div>
</div>
<script>
/**
 * Set comment block.
 *
 * @param  int    bugID
 * @param  object comment
 * @param  object user
 * @access public
 * @return void
 */
function setCommentBlock(bugID, comment, user)
{
    var $commentBlock = $('#commentTemp').html();
    var commentBlock = $commentBlock.replace(/%realname%/g, comment.realname).replace(/%date%/g, comment.date).replace(/%comment%/g, comment.comment).replace(/%commentID%/g, comment.id);

    var imgClass   = 'has-img';
    var userAvatar = user ? '<img src="' + user.avatar + '"/>' : '';
    if(!user || !user.avatar)
    {
        var name   = user.name ? user.name : (user.realname ? user.realname : comment.actor);
        imgClass   = 'has-text';
        userAvatar = '<span class="text text-len-' + name.replace(/[^\x00-\xff]/g, "00").length + '">' + name + '</span>';
    }

    commentBlock = commentBlock.replace(/%imgClass%/g, imgClass).replace(/%avatar%/g, userAvatar);
    $('#reviewComment' + bugID).append(commentBlock);
    $('#reviewComment' + bugID).parent().removeClass('hidden');

    var commentNum = parseInt($('.bug-comment' + bugID + ':first span').text());
    $('.bug-comment' + bugID + ' span').text(commentNum + 1);
    $('#reviewComment' + bugID + ' .commentEdit:not(:last)').addClass('hidden');
}

$(function()
{
    $.each(bugIDList, function(i, bugID)
    {
        if(reviewComments[bugID])
        {
            var comments = Object.values(reviewComments[bugID]);

            $.each(comments, function(i, comment)
            {
                setCommentBlock(bugID, comment, comment.user);
            });
        }
    });

    arrowTabs('reviewBugs', 1);

    $('#bugAccordion').on('hide.zui.collapse', function () {
        $('#bugAccordion .fold').removeClass('icon-caret-up').addClass('icon-caret-down');
    })

    $('#bugAccordion').on('show.zui.collapse', function () {
        $('#bugAccordion .fold').removeClass('icon-caret-up').addClass('icon-caret-down');
        $(this).find('.fold').addClass('icon-caret-up');
    })

    $('#reviewBugs .close').on('click', function()
    {
        parent.removeReviewBug();
    });

    $("#reviewBugs").on('click', '.btn-left', function()
    {
        arrowTabs('reviewBugs', 1);
    }).on('click', '.btn-right', function()
    {
        arrowTabs('reviewBugs', -2);
    }).on('click', '.commentEdit', function()
    {
        var $id = '#comment-' + $(this).data('id');
        $($id).find('.comment-content').addClass('hidden');
        $($id).find('.comment-form-div').removeClass('hidden');
    }).on('click', '.commentEditCancel', function()
    {
        $(this).closest('.comment-desc').find('.comment-content').removeClass('hidden');
        $(this).closest('.comment-desc').find('.comment-form-div').addClass('hidden');
    }).on('click', '.detail-unfold', function()
    {
        var fold = $(this).find('i').hasClass('icon-caret-down');
        $(this).html(fold ? repoLang.collapse + "<i class='icon icon-caret-up'></i>" : repoLang.expand + " <i class='icon icon-caret-down'></i>");

        var height = fold ? 'auto' : '75px';
        $(this).parent().find('.bug-detail').css('height', height);
    }).on('click', '.histories-list .switch-btn', function()
    {
        $(this).closest('li').toggleClass('show-changes');
        $(this).closest('li').find('.btn-mini').eq(2).remove();
    }).on('submit', '.commentForm', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(json)
            {
                $form.find('textarea').val('');
                var comment = $.parseJSON(json);
                setCommentBlock(comment.bugID, comment, comment.user);
            },
            beforeSubmit:function(formData, jqForm)
            {
                var form = jqForm[0];
                if(!form.comment.value)
                {
                    alert(contentError);
                    return false;
                }
            }
        });
        return false;
    }).on('submit', '.comment-edit-form', function()
    {
        var $form = $(this);
        $form.ajaxSubmit(
        {
            success:function(html)
            {
                var $comment = $form.closest('.comment-desc');
                $comment.find('.comment-content').html(html);

                $comment.find('.comment-content').removeClass('hidden');
                $comment.find('.comment-form-div').addClass('hidden');
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
    });
});

var distance = 0;

/**
 * Aarrow tabs area.
 *
 * @param  string domID
 * @param  number shift 1|-1
 * @param  bool   hideRightBtn
 * @access public
 * @return void
 */
function arrowTabs(domID, shift, hideRightBtn)
{
    if($('#' + domID).html() == '') return;

    var hasParent = $('#' + domID + ' .btn-left').length;
    var $leftBtn  = hasParent ? $('#' + domID + ' .btn-left')  : $('.btn-left');
    var $rightBtn = hasParent ? $('#' + domID + ' .btn-right') : $('.btn-right');

    $leftBtn.show();
    $rightBtn.show();
    if(hideRightBtn) $rightBtn.hide();

    var tabItemWidth = $('#' + domID + ' > .tabs-navbar > .nav-tabs')[0].clientWidth;
    var tabsWidth    = $('#' + domID)[0].clientWidth;
    if($('#' + domID + ' .close-bugs').length) tabsWidth = tabsWidth * 0.7;

    if(tabItemWidth < tabsWidth)
    {
        $leftBtn.hide();
        $rightBtn.hide();
        return;
    }

    distance += tabsWidth * shift * 0.2;
    if(distance > 0) distance = 0;
    if(distance == 0)
    {
        $leftBtn.hide();
    }

    if((tabItemWidth + distance) < tabsWidth * 0.75)
    {
        $rightBtn.hide();
        return arrowTabs(domID, 1, true);
    }

    $('#' + domID + ' > .tabs-navbar > .nav-tabs')[0].style.transform = 'translateX('+ distance +'px)';
}
</script>
