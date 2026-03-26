<?php
/**
 * Move reivew result to node.
 *
 * @access public
 * @return void
 */
public function moveResult2Node()
{
    $this->app->loadLang('approval');
    $reviews = $this->dao->select('id,status,result,reviewedBy')->from(TABLE_REVIEW)->where('deleted')->eq('0')->fetchAll('id');
    $results = $this->dao->select('*')->from(TABLE_REVIEWRESULT)
        ->where('type')->eq('review')
        ->fetchGroup('review', 'id');
    $nodes   = $this->dao->select('nodes')->from(TABLE_APPROVALFLOWSPEC)->orderBy('id_asc')->fetch('nodes');

    foreach($reviews as $reviewID => $review)
    {
        $reviewers = explode(',', $review->reviewedBy);
        if(isset($results[$reviewID]))
        {
            foreach($results[$reviewID] as $result)
            {
                if(in_array($result->reviewer, $reviewers))
                {
                    $key = array_search($result->reviewer, $reviewers);
                    unset($reviewers[$key]);
                }
            }
        }
        else
        {
            $results[$reviewID] = array();
        }

        foreach($reviewers as $reviewer)
        {
            if(!$reviewer) continue;

            $result = new stdclass();
            $result->review      = $reviewID;
            $result->type        = 'review';
            $result->result      = '';
            $result->reviewer    = $reviewer;
            $result->createdDate = $review->createdDate;

            $results[$reviewID][] = $result;
        }
    }

    if(empty($reviews)) return;

    foreach($reviews as $id => $review)
    {
        $approval = new stdclass();
        $approval->flow       = 1;
        $approval->objectType = 'review';
        $approval->objectID   = $id;
        $approval->version    = 1;
        $approval->nodes      = $nodes;
        $approval->status     = $review->status == 'pass' ? 'done' : 'doing';

        $this->dao->insert(TABLE_APPROVAL)->data($approval)->autoCheck()->exec();
        $approvalID = $this->dao->lastInsertID();

        $approvalObject = new stdclass();
        $approvalObject->approval   = $approvalID;
        $approvalObject->objectType = 'review';
        $approvalObject->objectID   = $id;
        $this->dao->insert(TABLE_APPROVALOBJECT)->data($approvalObject)->autoCheck()->exec();

        if(isset($results[$id]))
        {
            $reviewResults = $results[$id];
            foreach($reviewResults as $resultID => $result)
            {
                $node = new stdclass();
                $node->approval     = $approvalID;
                $node->title        = $this->lang->approval->common;
                $node->account      = $result->reviewer;
                $node->result       = $result->result ? ($result->result == 'pass' ? 'pass' : 'fail') : '';
                $node->node         = '3ewcj92p55e';
                $node->multipleType = 'and';
                $node->status       = $result->result ? 'done' : 'doing';
                $node->reviewedDate = $result->createdDate;
                $this->dao->insert(TABLE_APPROVALNODE)->data($node)->autoCheck()->exec();
            }
        }
    }
}
