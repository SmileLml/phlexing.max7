<?php
/**
 *
 * 构建创建bug页面数据。
 * Build form fields for create bug.
 *
 * @param  object    $bug
 * @param  array     $param
 * @param  string    $from
 * @access public
 * @return void
 */
public function buildCreateForm($bug, $param, $from)
{
    parent::buildCreateForm($bug, $param, $from);

    extract($param);

    if(!empty($fromType))
    {
        /* Init vars. */
        $moduleID   = 0;
        $fromObject = $this->loadModel((string)$fromType)->getById((int)$fromID);

        switch($fromType)
        {
        case 'feedback':
            $moduleID               = $fromObject->module;
            $this->view->feedbackID = $fromID;
            $this->view->feedback   = $fromObject;
            break;
        case 'ticket':
            $moduleID             = $fromObject->module;
            $this->view->ticketID = $fromID;
            $this->view->ticket   = $fromObject;
            break;
        }
        $this->view->fromType = $fromType;

        $bug = $this->updateBug($bug, array('title' => $fromObject->title, 'moduleID' => $moduleID, 'steps' => $fromObject->desc));
    }
}

/**
 * 创建 bug 后的返回结果。
 * respond after deleting.
 *
 * @param  object    $bug
 * @param  array     $params
 * @param  string    $message
 * @access public
 * @return bool|int
 */
public function responseAfterCreate($bug, $params, $message = '')
{
    extract($params);
    if(empty($fromType)) return parent::responseAfterCreate($bug, $params, $message);

    if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true));
    $location = $fromType == 'feedback' ? $this->createLink('feedback', 'adminView', "feedbackID=$fromID") : $this->createLink($fromType, 'view', "fromObjectID=$fromID");
    return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $location));
}

/**
 * 获取BUG详情页面的基本信息列表。
 * Get bug legend basic info.
 *
 * @param  object    $view
 * @access protected
 * @return array
 */
protected function getBasicInfoTable($view)
{
    extract((array)$view);

    $this->app->loadLang('product');
    $canViewProduct = common::hasPriv('project', 'view');
    $canBrowseBug   = common::hasPriv('bug', 'browse');
    $canViewPlan    = common::hasPriv('productplan', 'view');
    $canViewCase    = common::hasPriv('testcase', 'view');

    $branchTitle  = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
    $fromCaseName = $bug->case ? "#{$bug->case} {$bug->caseTitle}" : '';
    $productLink  = $bug->product && $canViewProduct ? helper::createLink('product',     'view',   "productID={$bug->product}")                           : '';
    $branchLink   = $bug->branch  && $canBrowseBug   ? helper::createLink('bug',         'browse', "productID={$bug->product}&branch={$bug->branch}")     : '';
    $planLink     = $bug->plan    && $canViewPlan    ? helper::createLink('productplan', 'view',   "planID={$bug->plan}&type=bug")                        : '';
    $fromCaseLink = $bug->case    && $canViewCase    ? helper::createLink('testcase',    'view',   "caseID={$bug->case}&caseVersion={$bug->caseVersion}") : '';

    $legendBasic = array();
    if(empty($product->shadow))    $legendBasic['product'] = array('name' => $this->lang->bug->product, 'text' => $product->name, 'href' => $productLink, 'attr' => array('data-app' => 'product'));
    if($product->type != 'normal') $legendBasic['branch']  = array('name' => $branchTitle,        'text' => $branchName,    'href' => $branchLink);
    $legendBasic['module'] = array('name' => $this->lang->bug->module, 'text' => $bug->module);
    if(empty($product->shadow) || !empty($project->multiple)) $legendBasic['productplan'] = array('name' => $this->lang->bug->plan, 'text' => $bug->planName, 'href' => $planLink);
    $legendBasic['fromCase']       = array('name' => $this->lang->bug->fromCase,       'text' => $fromCaseName, 'href' => $fromCaseLink, 'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
    $legendBasic['type']           = array('name' => $this->lang->bug->type,           'text' => zget($this->lang->bug->typeList, $bug->type));
    $legendBasic['severity']       = array('name' => $this->lang->bug->severity,       'text' => $bug->severity);
    $legendBasic['pri']            = array('name' => $this->lang->bug->pri,            'text' => $bug->pri);
    $legendBasic['status']         = array('name' => $this->lang->bug->status,         'text' => $this->processStatus('bug', $bug), 'attr' => array('class' => 'status-' . $bug->status));
    $legendBasic['activatedCount'] = array('name' => $this->lang->bug->activatedCount, 'text' => $bug->activatedCount ? $bug->activatedCount : '');
    $legendBasic['activatedDate']  = array('name' => $this->lang->bug->activatedDate,  'text' => formatTime($bug->activatedDate));
    $legendBasic['confirmed']      = array('name' => $this->lang->bug->confirmed,      'text' => $this->lang->bug->confirmedList[$bug->confirmed]);
    $legendBasic['assignedTo']     = array('name' => $this->lang->bug->lblAssignedTo,  'text' => $bug->assignedTo ? zget($users, $bug->assignedTo) . $this->lang->at . formatTime($bug->assignedDate) : '');
    $legendBasic['deadline']       = array('name' => $this->lang->bug->deadline,       'text' => formatTime($bug->deadline) . (isset($bug->delay) ? sprintf($this->lang->bug->notice->delayWarning, $bug->delay) : ''));
    $legendBasic['feedbackBy']     = array('name' => $this->lang->bug->feedbackBy,     'text' => $bug->feedbackBy);
    $legendBasic['notifyEmail']    = array('name' => $this->lang->bug->notifyEmail,    'text' => $bug->notifyEmail);
    $legendBasic['os']             = array('name' => $this->lang->bug->os,             'text' => $bug->os);
    $legendBasic['browser']        = array('name' => $this->lang->bug->browser,        'text' => $bug->browser);
    $legendBasic['keywords']       = array('name' => $this->lang->bug->keywords,       'text' => $bug->keywords);
    $legendBasic['mailto']         = array('name' => $this->lang->bug->mailto,         'text' => $bug->mailto);

    if($bug->feedback)
    {
        $legendBasic['feedback'] = array('name' => $lang->bug->found, 'text' => zget($users, $bug->found) . " #$bug->feedback $bug->feedbackTitle", 'href' => $this->createLink('feedback', 'adminview', "feedbackID=$bug->feedback"));
    }

    return $legendBasic;
}

/**
 *
 * 解析extras，如果bug来源于某个对象 (bug, case, testtask, todo, feedback) ，使用对象的一些属性对bug赋值。
 * Extract extras, if bug come from an object(bug, case, testtask, todo, feedback), get some value from object.
 *
 * @param  object    $bug
 * @param  array     $output
 * @access protected
 * @return object
 */
protected function extractObjectFromExtras($bug, $output)
{
    $bug = parent::extractObjectFromExtras($bug, $output);

    if(zget($output, 'fromType') == 'feedback' && isset($output['fromID']))
    {
        $feedback = $this->loadModel('feedback')->getById($output['fromID']);
        if(!empty($feedback->files))
        {
            $bug->files = isset($bug->files) ? array_merge($bug->files, $feedback->files) : $feedback->files;
            foreach($bug->files as $file) $file->name = $file->title;
            $bug->files = array_values($bug->files);
        }
    }

    return $bug;
}
