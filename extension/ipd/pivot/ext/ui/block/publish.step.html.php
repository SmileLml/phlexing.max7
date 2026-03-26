<?php
/**
 * The step publish view file of pivot design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     pivot
 * @link        https://www.zentao.net
 */
namespace zin;

$fnGenerateStepPublishConfig = function($pivotState) use($lang, $pivot, $groups, $fnAclBox)
{
    $clientLang = $this->app->getClientLang();
    $nameTabItems = array();
    $descTabItems = array();
    foreach($this->config->langs as $langKey => $currentLang)
    {
        $nameTabItems[] = tabPane
        (
            set::key('name_' . str_replace('-', '_', $langKey)),
            set::active($langKey == $clientLang),
            set::title($currentLang),
            div
            (
                set('data-lang', $langKey),
                input
                (
                    setID("name{$langKey}"),
                    setClass('pivot-lang-name'),
                    set::name("name[$langKey]"),
                    set::value(\zget($pivotState->names, $langKey, '')),
                    on::change()->do("changePivotLang(event, 'name')")
                )
            )
        );
        $descTabItems[] = tabPane
        (
            set::key('desc_' . str_replace('-', '_', $langKey)),
            set::active($langKey == $clientLang),
            set::title($currentLang),
            div
            (
                set('data-lang', $langKey),
                textarea
                (
                    setID("desc{$langKey}"),
                    setClass('pivot-lang-desc'),
                    set::name("desc[$langKey]"),
                    set::value(\zget($pivotState->descs, $langKey, '')),
                    on::change()->do("changePivotLang(event, 'desc')")
                )
            )
        );
    }

    return pivotConfig
    (
        set::title($lang->pivot->legendBasicInfo),
        set::saveText($lang->save),
        set::nextText($lang->pivot->nextButton['publish']),
        set::onSave('saveInfo(event)'),
        set::onNext("savePivot('published')"),
        to::heading
        (
            div
            (
                setClass('flex'),
                toolbar
                (
                    setClass('pr-1 add-button'),
                    btn(set(array('text' => $lang->pivot->setLang, 'data-toggle' => 'modal', 'data-name' => $lang->pivot->setLang, 'data-target' => '#setLang', 'class' => 'primary-pale')))
                )
            )
        ),
        div
        (
            setID('legendForm'),
            setClass('px-1'),
            formGroup
            (
                set::label($this->lang->pivot->group),
                picker
                (
                    setClass('pivot-group'),
                    set::name('group'),
                    set::items($groups),
                    set::value($pivotState->group),
                    set::multiple(true),
                    on::change()->do("changePivot(event, 'group')")
                )
            ),
            formGroup
            (
                set::label($this->lang->pivot->name),
                set::required(true),
                input
                (
                    setClass('pivot-name'),
                    set::name("name[$clientLang]"),
                    set::value($pivotState->name),
                    set('data-lang', $clientLang)
                )
            ),
            formGroup
            (
                set::label($this->lang->pivot->desc),
                textarea
                (
                    setClass('pivot-desc'),
                    set::name("desc[$clientLang]"),
                    set::value($pivotState->desc),
                    set::rows(7),
                    set('data-lang', $clientLang)
                )
            ),
            $fnAclBox($this->lang->pivot->aclList, $pivotState->acl, $pivotState->whitelist),
            on::change('#legendForm .pivot-name')->do("changePivot(event, 'name')"),
            on::change('#legendForm .pivot-desc')->do("changePivot(event, 'desc')"),
            on::change('#legendForm input[name="acl"]')->do('changePivotAcl(event)'),
            on::change('#legendForm select[name="whitelist[]"]')->do('changePivotWhitelist(event)'),
            modal
            (
                setID('setLang'),
                setData('backdrop', 'static'),
                set::title($this->lang->pivot->setLang),
                h::table
                (
                    setClass('table borderless'),
                    on::change('#setLang .pivot-lang-name')->do("changePivot(event, 'name')"),
                    on::change('#setLang .pivot-lang-desc')->do("changePivot(event, 'desc')"),
                    h::tr
                    (
                        h::th
                        (
                            set::width('80px'),
                            $this->lang->pivot->name
                        ),
                        h::td
                        (
                            div(setID('name')),
                            tabs($nameTabItems)
                        )
                    ),
                    h::tr
                    (
                        h::th($this->lang->pivot->desc),
                        h::td(div(setID('desc')), tabs($descTabItems))
                    ),
                    h::tr
                    (
                        h::th(),
                        h::td
                        (
                            btn
                            (
                                set::type('primary'),
                                setID('saveLang'),
                                $this->lang->save,
                                on::click()->do('saveInfo(event)')
                            )
                        )
                    )
                )
            )
        )
    );
};
