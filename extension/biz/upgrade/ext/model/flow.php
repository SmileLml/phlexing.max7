<?php
/**
 * Adjust feedback view data.
 *
 * @access public
 * @return void
 */
public function adjustFeedbackViewData()
{
    return $this->loadExtension('zentaobiz')->aujustFeedbackViewData();
}

/**
 *  Import zentao module for workflow.
 *
 * @param  string $vision
 * @access public
 * @return bool
*/
public function importBuildinModules($vision = 'all')
{
    return $this->loadExtension('zentaobiz')->importBuildinModules($vision);
}

/**
 * Import zentao module for lite.
 *
 * @access public
 * @return bool
*/
public function importLiteModules()
{
    return $this->loadExtension('zentaobiz')->importLiteModules();
}

/**
 * Add sub status for built-in modules.
 *
 * @access public
 * @return bool
 */
public function addSubStatus()
{
    return $this->loadExtension('zentaobiz')->addSubStatus();
}

/**
 * Add batch create action and batch edit action for exist flows.
 *
 * @access public
 * @return bool
 */
public function addDefaultActions()
{
    return $this->loadExtension('zentaobiz')->addDefaultActions();
}

/**
 * Import caselib module for workflow.
 *
 * @access public
 * @return bool
 */
public function importCaseLibModule()
{
    return $this->loadExtension('zentaobiz')->importCaseLibModule();
}

/**
 * Import epic and requirement module for workflow.
 *
 * @access public
 * @return bool
 */
public function importERURModules()
{
    return $this->loadExtension('zentaobiz')->importERURModules();
}

/**
 * Delete buildin fields.
 *
 * @access public
 * @return void
 */
public function deleteBuildinFields()
{
    return $this->loadExtension('zentaobiz')->deleteBuildinFields();
}

/**
 * processSubTables
 *
 * @access public
 * @return void
 */
public function processSubTables()
{
    return $this->loadExtension('zentaobiz')->processSubTables();
}

/**
 * Add workflow actions.
 *
 * @access public
 * @return void
 */
public function addWorkflowActions()
{
    return $this->loadExtension('zentaobiz')->addWorkflowActions();
}

/**
 * Process workflow layout.
 *
 * @access public
 * @return void
 */
public function processWorkflowLayout()
{
    return $this->loadExtension('zentaobiz')->processWorkflowLayout();
}

/**
 * Process workflow label.
 *
 * @access public
 * @return void
 */
public function processWorkflowLabel()
{
    return $this->loadExtension('zentaobiz')->processWorkflowLabel();
}

/**
 * Process workflow condition.
 *
 * @access public
 * @return void
 */
public function processWorkflowCondition()
{
    return $this->loadExtension('zentaobiz')->processWorkflowCondition();
}

/**
 * Process workflow fields.
 *
 * @access public
 * @return bool
 */
public function processWorkflowFields()
{
    return $this->loadExtension('zentaobiz')->processWorkflowFields();
}

public function processFlowStatus()
{
    return $this->loadExtension('zentaobiz')->processFlowStatus();
}

public function addMailtoFields()
{
    return $this->loadExtension('zentaobiz')->addMailtoFields();
}

public function initView4WorkflowDatasource()
{
    return $this->loadExtension('zentaobiz')->initView4WorkflowDatasource();
}

public function processFeedbackField()
{
    return $this->loadExtension('zentaobiz')->processFeedbackField();
}

public function addFileFields()
{
    return $this->loadExtension('zentaobiz')->addFileFields();
}

public function updateWorkflow4Execution()
{
    return $this->loadExtension('zentaobiz')->updateWorkflow4Execution();
}

public function adjustPrivBiz5_0_1()
{
    return $this->loadExtension('zentaobiz')->adjustPrivBiz5_0_1();
}

public function updateAttendStatus()
{
    return $this->loadExtension('zentaobiz')->updateAttendStatus();
}

public function addReportActions()
{
    return $this->loadExtension('zentaobiz')->addReportActions();
}

public function processViewFields()
{
    return $this->loadExtension('zentaobiz')->processViewFields();
}

public function processFlowPosition()
{
    return $this->loadExtension('zentaobiz')->processFlowPosition();
}

public function processBuildinBrowseFields()
{
    return $this->loadExtension('zentaobiz')->processBuildinBrowseFields();
}
