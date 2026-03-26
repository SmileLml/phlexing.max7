<?php
/**
 * The submit view file of submit module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     submit
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();

form
(
    formGroup
    (
        $review->status == 'reverting' ? setClass('hidden') : '',
        set::label($lang->review->reviewer),
        div(setID('reviewerBox'))
    ),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('editor')
    )
);

history();

jsVar('projectID', $review->project);
jsVar('type', $review->category);
jsVar('reviewID', $review->id);

pageJS(<<<JAVASCRIPT
$(function()
{
    var link = $.createLink('review', 'ajaxGetNodes', "project=" + projectID + '&object=' + type + '&productID=0&reviewID=' + reviewID);
    loadCurrentPage({url: link, selector: '#reviewerBox', partial: true});
});
JAVASCRIPT
);

render();
