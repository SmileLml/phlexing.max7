<?php
/**
 * Get Nc list.
 *
 * @param  int    $browseType
 * @param  string $orderBy
 * @param  object $pager
 * @param  string $status
 *
 * @access public
 * @return object
 */
public function getNcList($browseType, $orderBy, $pager, $status = '')
{
    $account = $this->app->user->account;
    return $this->dao->select('t1.*,t2.model')->from(TABLE_NC)->alias('t1')
        ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
        ->where('t1.deleted')->eq(0)
        ->andWhere('t2.deleted')->eq(0)
        ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in($this->app->user->view->projects . ',' . $this->app->user->view->sprints)->fi()
        ->beginIF($browseType == 'assignedToMe')->andWhere('t1.assignedTo')->eq($account)->fi()
        ->beginIF($browseType == 'createdByMe')->andWhere('t1.createdBy')->eq($account)->fi()
        ->beginIF($browseType == 'resolvedByMe')->andWhere('t1.resolvedBy')->eq($account)->fi()
        ->beginIF($browseType == 'closedByMe')->andWhere('t1.closedBy')->eq($account)->fi()
        ->beginIF($status)->andWhere('t1.status')->in($status)->fi()
        ->orderBy('t1.' . $orderBy)
        ->page($pager)
        ->fetchAll('id');
}

/**
 * Get products pairs.
 *
 * @access public
 * @return array
 */
public function getProductPairs()
{
    return $this->dao->select('id, name')->from(TABLE_PRODUCT)
        ->where('deleted')->eq(0)
        ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
        ->fetchPairs();
}

/**
 * AJAX: get project.
 *
 * @param  string $browseType
 * @access public
 * @return string
 */
public function ajaxGetProject($browseType)
{
    $reviewProject = $this->loadModel('project')->getReviewProject();
    $projectName = array();
    foreach($reviewProject as $project)
    {
        $projectName[] = $project->name;
    }
    $projectPinYin = common::convert2Pinyin($projectName);

    $projectLink = helper::createLink('my', 'review', "program=%s&browseType=$browseType");
    $listLink    = '';
    foreach($reviewProject as $item)
    {
        $listLink .= html::a(sprintf($projectLink, $item->id), '<i class="icon icon-folder-outline"></i>' . $item->name, '', 'title="' . $item->name . '" data-key="' . zget($projectPinYin, $item->name) . '"');
    }

    $html  = '<div class="table-row"><div class="table-col col-left"><div class="list-group">' . $listLink . '</div>';
    $html .= '<div class="col-footer">';
    $html .= html::a(sprintf($projectLink, 0), '<i class="icon icon-cards-view muted"></i>' . $this->lang->exportTypeList['all'], '', 'class="not-list-item"');
    $html .= '</div></div>';
    $html .= '<div class="table-col col-right"><div class="list-group"></div>';

    return $html;
}

/**
 * Print cell data.
 *
 * @param  object $col
 * @param  object $review
 * @param  array  $users
 * @param  array  $products
 * @param  array  $pendingList
 * @access public
 * @return void
 */
public function reviewPrintCell($col, $review, $users, $products = array(), $pendingList = array())
{
    $canView = common::hasPriv('my', 'review');
    $canBatchAction = false;
    $account = $this->app->user->account;
    $id = $col->id;
    if($col->show)
    {
        $class = "c-$id";
        $title = '';
        if($id == 'id') $class .= ' cell-id';
        if($id == 'status')
        {
            $class .= ' status-' . $review->status;
        }
        if($id == 'result')
        {
            $class .= ' status-' . $review->result;
        }
        if($id == 'title')
        {
            $class .= ' text-left';
            $title  = "title='{$review->title}'";
        }
        if($id == 'reviewedBy')
        {
            $reviewedByList = '';
            $reviewedBy     = explode(',', trim($review->reviewedBy, ','));

            foreach($reviewedBy as $key => $account)
            {
                $account = trim($account);
                if(empty($account)) continue;
                $reviewedByList  .= zget($users, $account) . " ";
                $reviewedBy[$key] = zget($users, $account);
            }

            $title      = "title='{$reviewedByList}'";
            $reviewedBy = count($reviewedBy) > 1 ? $reviewedBy[0] . ' ...' : $reviewedBy[0];
        }
        echo "<td class='" . $class . "' $title>";
        switch($id)
        {
        case 'id':
            if($canBatchAction)
            {
                echo html::checkbox('reviewIDList', array($review->id => '')) . html::a(helper::createLink('review', 'view', "reviewID=$review->id"), sprintf('%03d', $review->id));
            }
            else
            {
                printf('%03d', $review->id);
            }
            break;
        case 'title':
            echo html::a(helper::createLink('review', 'view', "reviewID=$review->id", '', '', $review->project), $review->title);
            break;
        case 'category':
            echo zget($this->lang->baseline->objectList, $review->category);
            break;
        case 'product':
            echo zget($products, $review->product);
            break;
        case 'version':
            echo $review->version;
            break;
        case 'status':
            echo zget($this->lang->review->statusList, $review->status);
            break;
        case 'reviewedBy':
            echo $reviewedBy;
            break;
        case 'createdBy':
            echo zget($users, $review->createdBy);
            break;
        case 'createdDate':
            echo $review->createdDate;
            break;
        case 'deadline':
            echo $review->deadline;
            break;
        case 'lastReviewedDate':
            echo $review->lastReviewedDate;
            break;
        case 'lastAuditedDate':
            echo $review->lastAuditedDate;
            break;
        case 'result':
            if($review->status == 'reviewing') break;
            echo zget($this->lang->review->resultList, $review->result);
            break;
        case 'auditResult':
            echo zget($this->lang->review->auditResultList, $review->auditResult);
            break;
        case 'actions':
            $params = "reviewID=$review->id&from={$this->app->rawMethod}";
            if(isset($pendingList[$review->id]))
            {
                common::printIcon('review', 'assess', $params, $review, 'list', 'glasses');
            }
            else
            {
                common::printIcon('review', 'assess', $params, $review, 'list', 'glasses', '', 'disabled');
            }

            $review->approval = isset($review->approval) ? $review->approval : 0;
            common::printIcon('approval', 'progress', "approvalID=$review->approval", $review, 'list', 'list-alt', '', 'iframe', 1);
        }
        echo '</td>';
    }
}

/**
 * 获取审计列表。
 * Get audit list.
 *
 * @param  string $browseType
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getAuditList($browseType, $orderBy, $pager = null)
{
    $account   = $this->app->user->account;
    $reviewSQL = '';
    if(in_array($browseType, array('auditByMe', 'createdByMe')))
    {
        $reviewSQL = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('actor')->eq($account)
            ->andWhere('objectType')->eq('review')
            ->andWhere('action')->eq($browseType == 'auditByMe' ? 'audited' : 'toaudit')
            ->get();
    }

    $reviews = $this->dao->select('t1.*, t2.version, t2.category, t2.product')->from(TABLE_REVIEW)->alias('t1')
        ->leftJoin(TABLE_OBJECT)->alias('t2')->on('t1.object=t2.id')
        ->where('t1.deleted')->eq(0)
        ->beginIF(in_array($browseType, array('auditByMe', 'createdByMe')))->andWhere('t1.id')->subIn($reviewSQL)->fi()
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll('id');

    $approvals = $this->dao->select('max(approval) as approval,objectID')->from(TABLE_APPROVALOBJECT)
        ->where('objectType')->eq('review')
        ->andWhere('objectID')->in(array_keys($reviews))
        ->groupBy('objectID')
        ->fetchAll('objectID');

    $pendingReviews = $this->loadModel('approval')->getPendingReviews('review');
    foreach($reviews as $id => $review)
    {
        $reviews[$id]->approval  = isset($approvals[$id]) ? $approvals[$id]->approval : 0;
        $reviews[$id]->isPending = !empty($pendingReviews[$id]);
    }

    return $reviews;
}

/**
 * 获取基线列表。
 * Get baseline list.
 *
 * @param  string $browseType
 * @param  string $orderBy
 * @param  object $pager
 * @access public
 * @return array
 */
public function getBaselineList($browseType, $orderBy, $pager = null)
{
    return $this->dao->select('*')->from(TABLE_OBJECT)
        ->where('deleted')->eq(0)
        ->andWhere('type')->eq('taged')
        ->beginIF($browseType == 'createdBy')->andWhere('createdBy')->eq($this->app->user->account)->fi()
        ->orderBy($orderBy)
        ->page($pager)
        ->fetchAll();
}
