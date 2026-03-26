<?php
/**
 * 展示Bug列表的相关变量。
 * Show the bug list related variables.
 *
 * @param  object    $execution
 * @param  object    $project
 * @param  int       $productID
 * @param  string    $branch
 * @param  array     $products
 * @param  string    $orderBy
 * @param  string    $type
 * @param  string    $build
 * @param  int       $param
 * @param  array     $bugs
 * @param  object    $pager
 * @access protected
 * @return void
 */
protected function assignBugVars($execution, $project, $productID, $branch, $products, $orderBy, $type, $param, $build, $bugs, $pager)
{
    parent::assignBugVars($execution, $project, $productID, $branch, $products, $orderBy, $type, $param, $build, $bugs, $pager);

    $executionBugs        = $this->view->bugs;
    $bugRelatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($executionBugs), 'bug', 'byRelation', true);
    foreach($executionBugs as $bug) $bug->relatedObject = zget($bugRelatedObjectList, $bug->id, 0);
    $this->view->bugs     = $executionBugs;
}

/**
 * 展示用例列表的相关变量。
 * Show the case list related variables.
 *
 * @param  int       $executionID
 * @param  int       $productID
 * @param  string    $branchID
 * @param  int       $moduleID
 * @param  int       $param
 * @param  string    $orderBy
 * @param  string    $type
 * @param  object    $pager
 * @access protected
 * @return void
 */
protected function assignTestcaseVars($executionID, $productID, $branchID, $moduleID, $param, $orderBy, $type, $pager)
{
    parent::assignTestcaseVars($executionID, $productID, $branchID, $moduleID, $param, $orderBy, $type, $pager);

    $executionCases        = $this->view->cases;
    $caseRelatedObjectList = $this->loadModel('custom')->getRelatedObjectList(array_keys($executionCases), 'testcase', 'byRelation', true);
    foreach($executionCases as $case)
    {
        $case->caseID        = $case->id;
        $case->relatedObject = zget($caseRelatedObjectList, $case->id, 0);
    }
    $this->view->cases = $executionCases;
}
