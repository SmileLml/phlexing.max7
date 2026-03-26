<?php
/**
* The showRelationGraph file of custom module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Qiyu Xie <xieqiyu@chandao.com>
* @package     custom
* @link        https://www.zentao.net
*/
namespace zin;

modalHeader(set::title($lang->custom->relationGraph), set::titleClass('font-bold text-lg'));

$graphID = uniqid();
jsVar('graphID', $graphID);
jsVar('usersAvatar', $usersAvatar);
jsVar('currentObjectType', $objectType);
jsVar('currentObjectID', $objectID);

div(
    setID('graphBox'),
    set::className('bg-gray-50'),
    graph(
        set::graphID($graphID),
        set::type('TreeGraph'),
        set::renderer('svg'),
        set::fitView(true),
        set::maxZoom(1),
        set::modes(array('default' => array('drag-canvas', 'zoom-canvas'))),
        set::defaultNode(array('type' => 'tree-node', 'anchorPoints' => array(array(0, 0.5), array(1, 0.5)))),
        set::defaultEdge(array('type' => 'cubic-horizontal')),
        set::layout(array(
            'type' => 'mindmap',
            'direction' => 'H',
            'getVGap' => jsRaw('window.getVGap'),
            'getHGap' => jsRaw('window.getHGap'),
            'getSide' => jsRaw('window.getSide'),
        )),
        set::data($graphData)
    )
);
