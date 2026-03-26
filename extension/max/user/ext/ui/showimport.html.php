<?php
/**
 * The showimport view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     user
 * @version     $Id$
 * @link        http://www.zentao.net
 */
namespace zin;
if(!empty($suhosinInfo))
{
    div(setClass('alert secondary'), $suhosinInfo);
}
elseif(empty($maxImport) and $allCount > $this->config->file->maxImport)
{
    $this->loadModel('transfer');
    panel
    (
        on::keyup('[name=maxImport]', 'recomputeTimes'),
        set::title($lang->transfer->import),
        html(sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport))),
        btn(setID('import'), setClass('primary'), on::click('setMaxImport'), $lang->import)
    );
    pageJS(<<<JAVASCRIPT
window.recomputeTimes = function()
{
    if(parseInt(\$('#maxImport').val())) \$('#times').html(Math.ceil(parseInt({$allCount}) / parseInt(\$('#maxImport').val())));
};

window.setMaxImport = function()
{
    let maxImport = parseInt(\$('#maxImport').val());
    \$.cookie.set('maxImport', maxImport, {expires:config.cookieLife, path:config.webRoot});
    loadPage(\$.createLink('user', 'showImport', 'pageID=1&maxImport=' + maxImport));
};
JAVASCRIPT

    );
}
else
{
    h::jsCall('$.getLib', 'md5.js', array('root' => $this->app->getWebRoot() . 'js/'));
    jsVar('roleGroup', $roleGroup);

    $fields = array();
    $fields['idIndex'] = array('name' => 'idIndex', 'label' => $lang->user->id, 'control' => 'static', 'required' => false, 'width' => '64px');
    unset($showFields['id']);
    foreach($showFields as $field)
    {
        if($field == 'type')
        {
            $fields[$field] = $config->user->form->batchCreate[$field];
            $fields[$field]['label']   = $lang->user->type;
            $fields[$field]['name']    = 'type';
            $fields[$field]['control'] = 'picker';
            $fields[$field]['items']   = $lang->user->typeList;
        }
        elseif($field == 'vision')
        {
            $field = 'visions';
            $fields[$field] = $config->user->form->batchEdit[$field];
            $fields[$field]['items'] = $visionList;
            $fields[$field]['ditto'] = true;
        }
        elseif($field == 'password')
        {
            $fields['password'] = $config->user->form->batchCreate['passwordfield'];
            $fields['password']['label']   = $lang->user->password;
            $fields['password']['name']    = 'password';
            $fields['password']['control'] = 'input';
            $fields['password']['ditto']   = true;
            $fields['password']['width']   = '160px';
        }
        elseif($field == 'group')
        {
            $fields[$field]['label']    = $lang->user->group;
            $fields[$field]['name']     = 'group';
            $fields[$field]['width']    = '240px';
            $fields[$field]['control']  = 'picker';
            $fields[$field]['multiple'] = true;
            $fields[$field]['items']    = $groupList;
            $fields[$field]['ditto']    = true;
        }
        else
        {
            $fields[$field] = $config->user->form->batchEdit[$field];
            $fields[$field]['ditto'] = false;
        }
        if(empty($fields[$field]['width'])) $fields[$field]['width'] = '136px';
    }
    $fields['account']['readonly'] = false;
    $fields['dept']['items']       = $depts;

    $insert      = true;
    $submitText  = $isEndPage ? $lang->save : $lang->file->saveAndNext;
    $isStartPage = $pagerID == 1;

    formBatchPanel
    (
        on::keyup('[name="verifyPassword"]', 'changePassword'),
        on::click('button[type=submit]', 'encryptPassword'),
        on::change('[data-name^=vision]', 'batchChangeVision'),
        on::change('input[name^=role]', 'batchChangeRole'),
        set::title($lang->user->batchImport),
        set::headingClass('justify-start'),
        $userAddWarning ? to::heading
        (
            div
            (
                setClass('text-danger font-sm'),
                $userAddWarning
            )
        ) : null,
        set::mode('edit'),
        set::items($fields),
        set::data(array_values($userData)),
        set::actions(array()),
        div
        (
            setClass('form-horz my-2'),
            formGroup
            (
                setClass('flex verify-box'),
                set::width('400px'),
                set::label($lang->user->verifyPassword),
                set::labelClass('w-10 mr-2'),
                set::control('password'),
                set::name('verifyPassword'),
                set::required(true)
            ),
            formHidden('verifyRand', $rand)
        ),
        div
        (
            setClass('toolbar form-actions form-group no-label'),
            $insert || $dataInsert !== '' ? btn(set::btnType('submit'), setClass('primary btn-wide'), $submitText) : btn(set('data-toggle', 'modal'), set('data-target', '#importNoticeModal'), setClass('primary btn-wide'), $submitText),
            btn(set::url($backLink), setClass('btn-back btn-wide'), $lang->goback),
            $this->session->insert && $dataInsert != '' ? formHidden('insert', $dataInsert) : null,
            formHidden('isEndPage', $isEndPage ? 1 : 0),
            formHidden('pagerID', $pagerID),
            html(sprintf($lang->file->importPager, $allCount, $pagerID, $allPager))
        ),
        $insert || $dataInsert !== '' ? null : modal
        (
            set::size('sm'),
            setID('importNoticeModal'),
            set::title($lang->importConfirm),
            formHidden('insert', 0),
            div
            (
                setClass('alert flex items-center'),
                icon(setClass('icon-2x alert-icon'), 'exclamation-sign'),
                div($lang->noticeImport)
            ),
            to::footer
            (
                btn(setClass('danger btn-wide'), set('onclick', 'submitForm("cover")'), $lang->importAndCover, set::btnType('submit')),
                btn(setClass('primary btn-wide'), set('onclick', 'submitForm("insert")'), $lang->importAndInsert, set::btnType('submit'))
            )
        )
    );
    pageJS(<<<JAVASCRIPT

window.submitForm = function(type)
{
    \$('#importNoticeModal [name=insert]').val(type == 'insert' ? 1 : 0);

    const rand = \$('input[name=verifyRand]').val();

    /* 加密当前登录用户的密码。*/
    /* Encrypt password of current user. */
    if(\$('input#verifyPassword').length > 0)
    {
        const password = \$('input#verifyPassword').val().trim();
        if(password && !verifyEncrypted)
        {
            \$('input#verifyPassword').val(md5(md5(password) + rand));
            verifyEncrypted = true;
        }
    }
};

/**
 * 密码改变时标记密码未加密。
 * Mark password unencrypted when password changes.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changePassword(event)
{
    if(targetID == 'verifyPassword') verifyEncrypted = false;
}
JAVASCRIPT

    );
}

