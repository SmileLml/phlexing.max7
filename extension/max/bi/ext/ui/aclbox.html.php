<?php
namespace zin;

$handleAclChange = jsCallback()->do(<<<'JS'
    const isPrivate    = $element.find('[name=acl]:checked').val() == 'private';
    const whitelistBox = $('.whitelistBox');
    whitelistBox.addClass('hidden');
    if(isPrivate) whitelistBox.removeClass('hidden');
JS
);

$fnAclBox = function($aclItems, $aclValue = 'open', $userValue = '', $aclLabel = '', $whitelistLabel = '') use ($handleAclChange, $lang)
{
    if(!$aclLabel)       $aclLabel       = $lang->bi->acl;
    if(!$whitelistLabel) $whitelistLabel = $lang->whitelist;
    return div
    (
        formGroup
        (
            set::label($aclLabel),
            setClass('aclBox'),
            radiolist
            (
                set::name('acl'),
                set::items($aclItems),
                set::value($aclValue),
                on::change($handleAclChange)
            )
        ),
        formGroup
        (
            setClass('whitelistBox'),
            $aclValue == 'open' ? setClass('hidden') : null,
            setStyle('padding: var(--form-grid-gap-y-half) 0 !important;'),
            set::label($whitelistLabel),
            whitelist
            (
                set::inputGroupClass('w-full'),
                set::name('whitelist[]'),
                set::value($userValue)
            )
        )
    );
};
