<?php
namespace zin;

set::id('progress');
set::title($title);
to::header(html($approvalNotice));

$keys    = array_keys($nodeGroup);
$lastKey = end($keys);
foreach($nodeGroup as $approvalID => $nodes)
{
    $items = '';
    foreach($nodes as $node)
    {
        if(empty($node->id)) continue;
        if($node->type == 'start' || $node->type == 'end')
        {
            $items .= $this->approval->buildReviewDesc($node, array('users' => $users, 'approval' => zget($approvals, $approvalID, array())));
        }
        elseif($node->type == 'branch')
        {
            foreach($node->branches as $branchNodes)
            {
                foreach($branchNodes->nodes as $branchNode)
                {
                    $items .= $this->approval->buildReviewDesc($branchNode, array('users' => $users, 'allReviewers' => $reviewerGroup[$approvalID], 'reviewers' => zget($reviewerGroup[$approvalID], $branchNode->id, array())), $nodePairs);
                }
            }
        }
        elseif(isset($reviewerGroup[$approvalID][$node->id]))
        {
            $items .= $this->approval->buildReviewDesc($node, array('users' => $users, 'reviewers' => $reviewerGroup[$approvalID][$node->id]), $nodePairs);
        }
        elseif(!empty($node->reviewers))
        {
            $items .= $this->approval->buildReviewDesc($node, array(), $nodePairs);
        }
    }

    ul(setClass('timeline timeline-tag-right'), html($items));

    $approvalID == $lastKey ? h::hr(setClass('scroll-into-view my-5')) : hr();
}
