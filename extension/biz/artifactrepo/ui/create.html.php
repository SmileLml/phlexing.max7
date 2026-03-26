<?php
/**
 * The create  view file of artifactrepo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     artifactrepo
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('serverID', key($servers));
formPanel
(
    set::title($lang->artifactrepo->create),
    set::actionsClass('w-2/3'),
    on::change('#repoName', 'onRepoChange'),
    formGroup
    (
        set::width('2/3'),
        set::name('name'),
        set::required(true),
        set::label($lang->artifactrepo->name)
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('products[]'),
        set::label($lang->repo->product),
        set::control(array('control' => 'picker', 'multiple' => true)),
        set::items($products)
    ),
    formGroup
    (
        set::width('2/3'),
        setClass('servers'),
        set::name('serverID'),
        set::required(true),
        set::label($lang->artifactrepo->serverID),
        set::items($servers),
        on::change('getArtifactRepo')
    ),
    formGroup(set::width('2/3'), set::name('repoName'), set::required(true), set::label($lang->artifactrepo->repoName), set::items(array())),
    formGroup
    (
        setClass('hidden'),
        set::name('type'),
        set::label($lang->artifactrepo->type),
        set::readonly(true),
        input
        (
            set::type('hidden'),
            set::name('format')
        )
    ),
    formGroup
    (
        setClass('hidden'),
        set::name('status'),
        set::label($lang->artifactrepo->status),
        set::readonly(true)
    ),
    formGroup
    (
        setClass('hidden'),
        set::name('url'),
        set::label($lang->artifactrepo->url),
        set::readonly(true)
    )
);
