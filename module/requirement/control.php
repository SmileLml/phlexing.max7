<?php
class requirement extends control
{
    /**
     * Create a requirement.
     *
     * @param  int    $productID
     * @param string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $objectID  projectID|executionID
     * @param  int    $bugID
     * @param  int    $planID
     * @param  int    $todoID
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function create($productID = 0, $branch = '', $moduleID = 0, $storyID = 0, $objectID = 0, $bugID = 0, $planID = 0, $todoID = 0, $extra = '')
    {
        echo $this->fetch('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&objectID=$objectID&bugID=$bugID&planID=$planID&todoID=$todoID&extra=$extra&storyType=requirement");
    }

    /**
     * Create a batch stories.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $moduleID
     * @param  int    $storyID
     * @param  int    $executionID projectID|executionID
     * @param  int    $plan
     * @param  string $storyType
     * @param  string $extra for example feedbackID=0
     * @access public
     * @return void
     */
    public function batchCreate($productID = 0, $branch = '', $moduleID = 0, $storyID = 0, $executionID = 0, $plan = 0, $storyType = 'requirement', $extra = '')
    {
        echo $this->fetch('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=$storyID&executionID=$executionID&plan=$plan&storyType=requirement&extra=$extra");
    }

    /**
     * View a requirement.
     *
     * @param  int    $storyID
     * @param  int    $version
     * @param  int    $param     executionID|projectID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function view($storyID, $version = 0, $param = 0)
    {
        echo $this->fetch('story', 'view', "storyID=$storyID&version=$version&param=$param&storyType=requirement");
    }

    /**
     * Edit a requirement.
     *
     * @param  int    $storyID
     * @param  string $kanbanGroup
     * @access public
     * @return void
     */
    public function edit($storyID, $kanbanGroup = 'default')
    {
        echo $this->fetch('story', 'edit', "storyID=$storyID&kanbanGroup=$kanbanGroup&storyType=requirement");
    }

    /**
     * Batch edit requirement.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param string $branch
     * @param  string $storyType
     * @param  string $from
     * @access public
     * @return void
     */
    public function batchEdit($productID = 0, $executionID = 0, $branch = '', $storyType = 'requirement', $from = '')
    {
        echo $this->fetch('story', 'batchEdit', "productID=$productID&executionID=$executionID&branch=$branch&storyType=requirement&from=$from");
    }

    /**
     * 用户需求详情页，关联软件需求，将用户需求关联到软件需求。
     * Link story and requirement.
     *
     * @param  int    $storyID
     * @param  string $type          linkStories|linkRelateUR|linkRelateSR
     * @param  int    $linkedStoryID
     * @param  string $browseType    ''|bySearch
     * @param  int    $queryID
     * @access public
     * @return void
     */
    public function linkStory($storyID, $type = 'linkStories', $linkedStoryID = 0, $browseType = '', $queryID = 0)
    {
        echo $this->fetch('story', 'linkStory', "storyID=$storyID&type=$type&linkedStoryID=$linkedStoryID&browseType=$browseType&queryID=$queryID&storyType=requirement");
    }

    /**
     * 导出需求数据。
     * Get the data of the requiremens to export.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  int    $executionID
     * @param  string $browseType
     * @access public
     * @return void
     */
    public function export($productID, $orderBy, $executionID = 0, $browseType = '')
    {
        echo $this->fetch('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$executionID&browseType=$browseType&storyType=requirement");
    }

    /**
     * 导入需求数据。
     * Import the data of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $storyType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function import($productID, $branch = 0, $storyType = 'requirement', $projectID = 0)
    {
        echo $this->fetch('story', 'import', "productID=$productID&branch=$branch&storyType=requirement&projectID=$projectID");
    }

    /**
     * 显示导入需求数据的页面。
     * Show the page of importing the data of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  int    $projectID
     * @param  int    $pagerID
     * @param  int    $maxImport
     * @param  string $insert
     * @access public
     * @return void
     */
    public function showImport($productID, $branch = 0, $type = 'requirement', $projectID = 0, $pagerID = 1, $maxImport = 0, $insert = '')
    {
        echo $this->fetch('story', 'showImport', "productID=$productID&branch=$branch&type=requirement&projectID=$projectID&pagerID=$pagerID&maxImport=$maxImport&insert=$insert");
    }

    /**
     * 导出需求模板。
     * Export the template of the requiremens.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function exportTemplate($productID, $branch = 0, $storyType = 'requirement')
    {
        echo $this->fetch('story', 'exportTemplate', "productID=$productID&branch=$branch&storyType=requirement");
    }

    /**
     * Delete a requirement.
     *
     * @param  int    $storyID
     * @param  string $confirm   yes|no
     * @param  string $from      taskkanban
     * @access public
     * @return void
     */
    public function delete($storyID, $confirm = 'no', $from = '')
    {
        echo $this->fetch('story', 'delete', "storyID=$storyID&confirm=$confirm&from=$from&storyType=requirement");
    }

    /**
     * Change a requirement.
     *
     * @param  int    $storyID
     * @param  string $from
     * @access public
     * @return void
     */
    public function change($storyID, $from = '')
    {
        echo $this->fetch('story', 'change', "storyID=$storyID&from=$from&storyType=requirement");
    }

    /**
     * Review a requirement.
     *
     * @param  int    $storyID
     * @param  string $from      product|project
     * @access public
     * @return void
     */
    public function review($storyID, $from = 'product')
    {
        echo $this->fetch('story', 'review', "storyID=$storyID&from=$from&storyType=requirement");
    }

    /**
     * Submit review.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function submitReview($storyID)
    {
        echo $this->fetch('story', 'submitReview', "storyID=$storyID&storyType=requirement");
    }

    /**
     * Batch review requirements.
     *
     * @param  string $result
     * @param  string $reason
     * @access public
     * @return void
     */
    public function batchReview($result, $reason = '')
    {
        echo $this->fetch('story', 'batchReview', "result=$result&reason=$reason&storyType=requirement");
    }

    /**
     * Recall the requirement review or requirement change.
     *
     * @param  int    $storyID
     * @param  string $from      list
     * @param  string $confirm   no|yes
     * @access public
     * @return void
     */
    public function recall($storyID, $from = 'list', $confirm = 'no')
    {
        echo $this->fetch('story', 'recall', "storyID=$storyID&from=$from&confirm=$confirm&storyType=requirement");
    }

    /**
     * 需求的指派给页面。
     * Assign the requirement to a user.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function assignTo($storyID)
    {
        echo $this->fetch('story', 'assignTo', "storyID=$storyID");
    }

    /**
     * 关闭需求。
     * Close the requirement.
     *
     * @param  int    $storyID
     * @param  string $from      taskkanban
     * @access public
     * @return void
     */
    public function close($storyID, $from = '')
    {
        echo $this->fetch('story', 'close', "storyID=$storyID&from=$from&storyType=requirement");
    }

    /**
     * 批量关闭需求。
     * Batch close the requiremens.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $storyType
     * @param  string $from        contribute|work
     * @access public
     * @return void
     */
    public function batchClose($productID = 0, $executionID = 0, $storyType = 'requirement', $from = '')
    {
        echo $this->fetch('story', 'batchClose', "productID=$productID&executionID=$executionID&storyType=requirement&from=$from");
    }

    /**
     * 激活需求。
     * Activate a requirement.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function activate($storyID)
    {
        echo $this->fetch('story', 'activate', "storyID=$storyID&storyType=requirement");
    }

    /**
     * 查看需求的报告。
     * The report page.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  string $storyType
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  string $chartType
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function report($productID, $branchID, $storyType = 'requirement', $browseType = 'unclosed', $moduleID = 0, $chartType = 'pie', $projectID = 0)
    {
        echo $this->fetch('story', 'report', "productID=$productID&branchID=$branchID&storyType=requirement&browseType=$browseType&moduleID=$moduleID&chartType=$chartType&projectID=$projectID");
    }

    /**
     * Batch change branch.
     *
     * @param  int    $branchID
     * @param  string $confirm  yes|no
     * @param  string $storyIdList
     * @access public
     * @return void
     */
    public function batchChangeBranch($branchID, $confirm = '', $storyIdList = '')
    {
        echo $this->fetch('story', 'batchChangeBranch', "branchID=$branchID&confirm=$confirm&storyIdList=$storyIdList&storyType=requirement");
    }

    /**
     * Batch change parent.
     *
     * @param  int    $productID
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function batchChangeParent($productID = 0, $storyType = 'requirement')
    {
        echo $this->fetch('story', 'batchChangeParent', "productID=$productID&storyType=requirement");
    }

    /**
     * Batch change grade.
     *
     * @param  int    $grade
     * @param  string $storyType requirement|story|epic
     * @access public
     * @return void
     */
    public function batchChangeGrade($grade, $storyType = 'requirement')
    {
        echo $this->fetch('story', 'batchChangeGrade', "grade=$grade&storyType=requirement");
    }

    /**
     * Batch assign to.
     *
     * @param  string $storyType story|requirement
     * @access public
     * @return void
     * @param string $assignedTo
     */
    public function batchAssignTo($storyType = 'requirement', $assignedTo = '')
    {
        echo $this->fetch('story', 'batchAssignTo', "storyType=requirement&assignedTo=$assignedTo");
    }

    /**
     * Batch change the module of story.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function batchChangeModule($moduleID)
    {
        echo $this->fetch('story', 'batchChangeModule', "moduleID=$moduleID&storyType=requirement");
    }

    /**
     * Batch change plan.
     *
     * @param  int    $planID
     * @param  int    $oldPlanID
     * @access public
     * @return void
     */
    public function batchChangePlan($planID, $oldPlanID = 0)
    {
        echo $this->fetch('story', 'batchChangePlan', "planID=$planID&oldPlanID=$oldPlanID&storyType=epic");
    }

    /**
     * 关联用户需求。
     * Link related requirements.
     *
     * @param  int    $storyID
     * @param  string $browseType
     * @param  string $excludeStories
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkRequirements($storyID, $browseType = '', $excludeStories = '', $param = 0, $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        echo $this->fetch('story', 'linkRequirements', "storyID=$storyID&browseType=$browseType&excludeStories=$excludeStories&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Ajax get the stories of a user.
     *
     * @param  int     $userID
     * @param  string  $id
     * @param  int     $appendID
     * @param  string  $storyType
     * @access public
     * @return void
     */
    public function ajaxGetUserStories($userID = 0, $id = '', $appendID = 0, $storyType = 'requirement')
    {
        echo $this->fetch('story', 'ajaxGetUserStories', "userID=$userID&id=$id&appendID=$appendID&storyType=requirement");
    }

    /**
     * 需求的父需求变更时，子需求确认变更。
     * Confirm the change of the parent story.
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function processStoryChange($storyID)
    {
        echo $this->fetch('story', 'processStoryChange', "storyID=$storyID");
    }
}
