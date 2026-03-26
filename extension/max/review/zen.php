<?php
class reviewZen extends review
{
    /**
     * 获取当前审批节点。
     * Process the review for view.
     *
     * @param  int    $reviewID
     * @access public
     * @return void
     */
    public function processApprovalForView($reviewID)
    {
        $approval  = $this->loadModel('approval')->getByObject('review', $reviewID);
        $doingNode = $this->dao->select('node,COUNT(1) as count')->from(TABLE_APPROVALNODE)->where('approval')->eq($approval->id)->andWhere('status')->eq('doing')->andWhere('type')->eq('review')->groupBy('node')->fetch();

        if(!$doingNode) return false;

        $nodeGroups     = $this->approval->getNodeOptions(json_decode($approval->nodes));
        $currentNode    = zget($nodeGroups, $doingNode->node);
        $canRevertNodes = $this->loadModel('approval')->getCanRevertNodes($approval->id, $currentNode);

        if($currentNode->multiple == 'or' || $doingNode->count <= 1) $this->config->review->assess->requiredFields .= 'setReviewer,';
        if(isset($currentNode->commentType) && $currentNode->commentType == 'required') $this->config->review->assess->requiredFields .= 'opinion,';
        $this->config->review->assess->requiredFields = trim($this->config->review->assess->requiredFields, ',');

        $this->view->currentNode    = $currentNode;
        $this->view->canRevertNodes = $canRevertNodes;
    }

    /**
     * 获取审批节点的审批人和抄送人。
     * Get node reviewer pairs.
     *
     * @param  int    $reviewID
     * @access public
     * @return array
     */
    public function getNodeReviewerPairs($reviewID)
    {
        $nodeReviewerPairs = array();

        $approval      = $this->loadModel('approval')->getByObject('review', $reviewID);
        $nodeReviewers = $this->approval->getNodeReviewers($approval->id);
        foreach($nodeReviewers as $nodeID => $nodeList)
        {
            if(empty($nodeList['reviewers'])) continue;

            foreach($nodeList['reviewers'] as $user)
            {
                $nodeReviewerPairs[$nodeID]['reviewers'][] = $user['account'];
            }
            $nodeReviewerPairs[$nodeID]['ccs'] = $nodeList['ccs'];
        }

        return $nodeReviewerPairs;
    }

    /**
     * 导出评审报告的word。
     * Export review report by html.
     *
     * @param  object $review
     * @param  int    $approval
     * @param  object $data
     * @access public
     * @return void
     * @param int $approvalID
     */
    public function exportWord($review, $approvalID = 0, $data = null)
    {
        $this->loadModel('baseline');

        $approval       = $this->loadModel('approval')->getByID($approvalID);
        $approvalIDList = $this->loadModel('approval')->getApprovalIDByObjectID($review->id, 'review');
        $approvalID     = empty($approvalID) ? end($approvalIDList) : $approvalID;
        $approvalNode   = $this->approval->getApprovalNodeByApprovalID($approvalID);
        $issues         = $this->loadModel('reviewissue')->getIssueByReview($review->id, $review->project, 'review', 'all', 'all', $approvalID);

        /* Get reviewers. */
        $reviewers = array();
        foreach($approvalNode as $node)
        {
            if(!empty($node->reviewedBy) and !in_array($node->reviewedBy, $reviewers))
            {
                $reviewers[] = $node->reviewedBy;
            }
        }

        $users       = $this->loadModel('user')->getPairs('noclosed|noletter');
        $efforts     = $this->loadModel('effort')->getByObject('review', $review->id, 'id', $approvalID);
        $objectScale = $this->review->getObjectScale($review);

        $consumed    = 0;
        $accountConsumed = array();
        unset($efforts['typeList']);
        foreach($efforts as $effort)
        {
            $accountConsumed[$effort->account][] = $effort->consumed;
            $consumed += empty($effort->consumed) ? 0 : $effort->consumed;
        }

        $this->post->set('review', $review);
        $this->post->set('reviewer', $reviewers);
        $this->post->set('approval', $approval);
        $this->post->set('reviewerCount', count($reviewers));
        $this->post->set('issues', $issues);
        $this->post->set('objectScale', (float)$objectScale);
        $this->post->set('consumed', $consumed);
        $this->post->set('accountConsumed', $accountConsumed);
        $this->post->set('users', $users);
        $this->post->set('approvalNode', $approvalNode);
        $this->fetch('file', 'exportReviewReport', $_POST);
    }

    /**
     * 导出评审报告的HTML。
     * Export review report by html.
     *
     * @param  object $review
     * @param  int    $approval
     * @param  object $data
     * @access public
     * @return void
     * @param int $approvalID
     */
    public function exportHTML($review, $approvalID = 0, $data = null)
    {
        $jqueryCode = "$(function(){\n";
        $jqueryCode .= "})\n";
        $this->session->set('notHead', true);
        $this->config->webRoot = getWebRoot(true);
        $output = $this->fetch('review', 'report', array('reviewID' => $review->id, 'approvalID' => $approvalID));
        $sysURL = common::getSysURL();
        $output = str_replace('<img src="', '<img src="' . $sysURL, $output);
        $output = preg_replace('/<i[^>]*>(.*?)<\/i>/s', '', $output);
        $this->session->set('notHead', false);
        $css    = '<style>' . $this->getCSS('review', 'report') . '</style>';
        $css   .= '<style>' . file_get_contents($this->app->getWwwRoot() . 'js/zui3/zui.zentao.css') . '</style>';
        $css   .= "<style>#header{display: none}</style>";
        $js     = '<script>' . $this->getJS('review', 'report') . $jqueryCode . '</script>';
        /* Get zui zentao js. */
        $jsFile = $this->app->getWwwRoot() . 'js/zui3/zui.zentao.js';
        $jquery = '<script>' . file_get_contents($jsFile) . '</script>';
        $jquery .= '<script>' . file_get_contents($jsFile) . '</script>';
        $content = "<!DOCTYPE html>\n<html lang='zh-cn'>\n<head>\n<meta charset='utf-8'>\n<title>{$review->title}</title>\n$jquery\n$css\n$js\n</head>\n<body>\n<h1>{$review->title}</h1>\n$output\n</body></html>";
        $this->fetch('file',  'sendDownHeader', array('fileName' => $data->fileName, 'fileType' => $data->fileType, 'content' =>$content));
    }
}
