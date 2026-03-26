<?php
/**
 * The ajaxGetNodes view file of review module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     review
 * @link        https://www.zentao.net
 */
namespace zin;

$content = null;
if(!$object)
{
    $content = div
    (
        setClass('h-8 content-center'),
        span($lang->noData)
    );
}
else
{
    $nodeTrs = array();
    foreach($nodes as $node)
    {
        $rangeUsers = $users;
        if(isset($node['range']['reviewer']))
        {
            $rangeUsers = array();
            foreach($node['range']['reviewer'] as $range) $rangeUsers[$range] = zget($users, $range, array());
        }

        $reviewers = array();
        foreach(array('appointees', 'role', 'position', 'self', 'upLevel', 'superior', 'superiorList', 'productRole', 'projectRole', 'executionRole') as $key)
        {
            if(isset($node[$key]['reviewer']))
            {
                foreach($node[$key]['reviewer'] as $reviewer) $reviewers[$reviewer] = zget($users, $reviewer);
            }
        }

        $ccRangeUsers = $users;
        if(isset($node['range']['ccer']))
        {
            $ccRangeUsers = array();
            foreach($node['range']['ccer'] as $range) $ccRangeUsers[$range] = zget($users, $range, array());
        }

        $ccers = array();
        foreach(array('appointees', 'role', 'position', 'upLevel', 'superior') as $key)
        {
            if(isset($node[$key]['ccer']))
            {
                foreach($node[$key]['ccer'] as $ccer) $ccers[$ccer] = zget($users, $ccer);
            }
        }

        $lastNode = zget($nodeReviewerPairs, $node['id'], array());

        $nodeTrs[] = h::tr
        (
            h::td
            (
                setClass('text-center'),
                $node['title'],
                formHidden('id[]', $node['id'])
            ),
            h::td
            (
                setClass('text-center'),
                in_array('reviewer', $node['types']) ? div
                (
                    picker
                    (
                        set::id("reviewer{$node['id']}"),
                        set::name('reviewer[' . $node['id'] . '][]'),
                        set::items(array_diff(array_intersect_key($users, $rangeUsers), $reviewers)),
                        $lastNode ? set::value($lastNode['reviewers']) : null,
                        set::multiple(true)
                    ),
                    $reviewers ? div
                    (
                        setClass('otherReviewer mt-2.5'),
                        $lang->approval->otherReviewer . join(',', $reviewers)
                    ) : null
                ) : div
                (
                    formHidden('reviewer[' . $node['id'] . '][]', ''),
                    $reviewers ? join(',', $reviewers) : null
                )
            ),
            h::td
            (
                setClass('text-center'),
                in_array('ccer', $node['types']) ? div
                (
                    picker
                    (
                        set::name('ccer[' . $node['id'] . '][]'),
                        set::items(array_diff(array_intersect_key($users, $rangeUsers), $ccers)),
                        $lastNode ? set::value($lastNode['ccs']) : null,
                        set::multiple(true)
                    ),
                    $ccers ? div
                    (
                        setClass('otherCcer mt-2.5'),
                        $lang->approval->otherCcer . join(',', $ccers)
                    ) : null
                ) : div
                (
                    formHidden('ccer[' . $node['id'] . '][]', ''),
                    $ccers ? join(',', $ccers) : null
                )
            )
        );
    }

    $content = h::table
    (
        setClass('table bordered'),
        h::thead
        (
            h::tr
            (
                h::th
                (
                    setClass('text-center'),
                    width('1/5'),
                    $lang->approval->node
                ),
                h::th
                (
                    setClass('text-center'),
                    width('2/5'),
                    $lang->approval->reviewer
                ),
                h::th
                (
                    setClass('text-center'),
                    width('2/5'),
                    $lang->approval->ccer
                )
            )
        ),
        h::tbody
        (
            $nodeTrs
        )
    );
}

div
(
    setID('reviewerBox'),
    setStyle('width', '100%'),
    $content
);

render();
