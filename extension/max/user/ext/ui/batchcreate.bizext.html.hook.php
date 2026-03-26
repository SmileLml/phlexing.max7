<?php
namespace zin;
$userCount = $userMaxCount = 0;
if(data('properties.user'))
{
    $lang = data('lang');

    global $app;
    $userMaxCount = data('properties.user.value');
    $userCount    = $app->control->dao->select("COUNT('*') as count")->from(TABLE_USER)->where('deleted')->eq(0)->fetch('count');

    $checkWhenSubmit = jsCallback()
        ->const('userCount', $userCount)
        ->const('userMaxCount', $userMaxCount)
        ->const('noticeUserCreate', str_replace('%maxcount%', $userMaxCount, $lang->user->noticeUserCreate))
        ->do(<<<'JS'
var allUserCount = parseInt(userCount);

$('[data-name=account]').find('input').each(function(){
    if($(this).val().length > 0) allUserCount += 1;
});

if(allUserCount > userMaxCount)
{
    zui.Modal.alert(noticeUserCreate.replace('%usercount%', allUserCount));
    return false;
}
JS
);
    query('.form-actions .btn.primary')->on('click', $checkWhenSubmit);
    query('.form-actions .btn.primary')->append(''); // 删掉之后query->on('click')无法初始化，zui-init属性为空，不可删除
}
